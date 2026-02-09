<!DOCTYPE html>
<html>
<head>
    <title>Редактирование теста</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link href="https://unpkg.com/survey-core/survey-core.min.css" rel="stylesheet">
    <script src="https://unpkg.com/survey-core/survey.core.min.js"></script>
    <script src="https://unpkg.com/survey-js-ui/survey-js-ui.min.js"></script>
    <link href="https://unpkg.com/survey-creator-core/survey-creator-core.min.css" rel="stylesheet">
    <script src="https://unpkg.com/survey-creator-core/survey-creator-core.min.js"></script>
    <script src="https://unpkg.com/survey-creator-js/survey-creator-js.min.js"></script>
    <script src="https://unpkg.com/survey-creator-core/survey-creator-core.i18n.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .svc-creator__non-commercial-text { display: none; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-gray-50">
    <div class="header bg-gray-100 p-4 border-b border-gray-200">
        <div class="flex items-center justify-between w-full">
            <h2 class="text-2xl sm:text-4xl font-bold text-gray-800">{{ $test->name }}</h2>
            <button id="saveBtn" class="ml-4 px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                Сохранить
            </button>
        </div>
    </div>

    <div id="surveyCreator" class="h-[calc(100vh-72px)]"></div>

    <form id="saveForm" method="POST" action="{{ route('saveTest') }}" class="hidden">
        @csrf
        <input type="hidden" name="id" id="formSurveyId" value="{{ $test->id }}">
        <input type="hidden" name="content" id="formSurveyJson">
        <input type="hidden" name="max_score" id="formMaxScore">
    </form>

    <script>
        Survey.Serializer.addProperty("itemvalue", { name: "score:number" });

        SurveyCreator.localization.currentLocale = "ru";
        SurveyCreator.localization.locales["ru"].pe = {
            ...SurveyCreator.localization.locales["ru"].pe,
            clear: "Очистить",
            change: "Поменять",
            set: "Установить",
            correctAnswer: "правильный ответ",
            clearCorrectAnswer: "Сбросить правильный ответ",
            score: "Баллы"
        };

        const testData = @json($test->content ?? '{}');
        const initialData = {
            name: '{{ $test->name }}',
            content: testData && typeof testData === 'object' ? testData : JSON.parse(testData || '{}')
        };

        const creator = new SurveyCreator.SurveyCreator({
            showLogicTab: true,
            isAutoSave: true,
            showTranslationTab: false
        });

        creator.text = JSON.stringify(initialData.content);
        creator.render("surveyCreator");

        let initialJson = creator.text;
        let hasChanges = false;

        creator.onModified.add(() => {
            hasChanges = creator.text !== initialJson;
        });

        function beforeUnloadHandler(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'У вас есть несохраненные изменения. Вы уверены, что хотите уйти?';
                return e.returnValue;
            }
        }
        window.addEventListener('beforeunload', beforeUnloadHandler);

        function normalizeChoiceArray(arr) {
            for (let i = 0; i < arr.length; i++) {
                let ch = arr[i];
                if (typeof ch === 'string') {
                    ch = { value: ch, text: ch, score: 0 };
                }
                if (typeof ch === 'object' && ch !== null) {
                    if (ch.value === undefined && ch.text !== undefined) ch.value = ch.text;
                    if (ch.text === undefined && ch.value !== undefined) ch.text = ch.value;
                    if (ch.score === undefined || ch.score === null) ch.score = 0;
                    else ch.score = Number(ch.score) || 0;
                    arr[i] = ch;
                } else {
                    arr[i] = { value: String(ch), text: String(ch), score: 0 };
                }
            }
        }

        function normalizeScoresInSurvey(surveyJSON) {
            if (!surveyJSON || !surveyJSON.pages) return;

            surveyJSON.pages.forEach(page => {
                if (!page.elements) return;
                page.elements.forEach(q => {
                    if (Array.isArray(q.choices) && q.choices.length) normalizeChoiceArray(q.choices);
                    if (Array.isArray(q.rateValues) && q.rateValues.length) normalizeChoiceArray(q.rateValues);
                    if (Array.isArray(q.columns) && q.columns.length) normalizeChoiceArray(q.columns);

                    if (q.type === 'matrixdropdown' && Array.isArray(q.columns)) {
                        q.columns.forEach(col => {
                            if (Array.isArray(col.choices) && col.choices.length) normalizeChoiceArray(col.choices);
                        });
                    }

                    if (Array.isArray(q.items) && q.items.length) normalizeChoiceArray(q.items);

                    if (Array.isArray(q.elements) && q.elements.length) {
                        q.elements.forEach(inner => {
                            if (Array.isArray(inner.choices) && inner.choices.length) normalizeChoiceArray(inner.choices);
                        });
                    }

                    if (q.type === 'boolean') {
                        if (q.score === undefined || q.score === null) q.score = 1;
                        else q.score = Number(q.score) || 0;
                    }

                    if ((q.type === 'text' || q.type === 'comment' || q.type === 'number') &&
                        q.correctAnswer !== undefined && q.correctAnswer !== null) {
                        if (q.score === undefined || q.score === null) q.score = 1;
                        else q.score = Number(q.score) || 0;
                    }

                    if (q.score !== undefined && q.type !== 'boolean') {
                        q.score = Number(q.score) || 0;
                    }
                });
            });
        }

        function calculateMaxScore(surveyJSON) {
            let maxScore = 0;
            if (!surveyJSON || !Array.isArray(surveyJSON.pages)) return 0;

            surveyJSON.pages.forEach(page => {
                if (!Array.isArray(page.elements)) return;
                page.elements.forEach(q => {
                    const type = q.type;

                    if (Array.isArray(q.choices) && q.choices.length) {
                        if (type === 'checkbox') {
                            q.choices.forEach(c => {
                                maxScore += Number(c.score) || 0;
                            });
                        } else {
                            const scores = q.choices.map(c => Number(c.score) || 0);
                            if (scores.length) maxScore += Math.max(...scores);
                        }
                    }
                    else if (Array.isArray(q.rateValues) && q.rateValues.length) {
                        const scores = q.rateValues.map(c => Number(c.score) || 0);
                        if (scores.length) maxScore += Math.max(...scores);
                    }
                    else if (Array.isArray(q.columns) && q.correctAnswer) {
                        maxScore += Object.keys(q.correctAnswer || {}).length;
                    }
                    else if (type === 'boolean') {
                        maxScore += Number(q.score) || 0;
                    }
                    else if (type === 'text' || type === 'comment' || type === 'number') {
                        if (q.score !== undefined && q.score !== null) {
                            maxScore += Number(q.score) || 0;
                        }
                        else if (q.correctAnswer !== undefined && q.correctAnswer !== null) {
                            maxScore += 1;
                        }
                    }
                    else {
                        if (q.score !== undefined && q.score !== null) {
                            maxScore += Number(q.score) || 0;
                        }
                    }
                });
            });

            return maxScore;
        }

        document.getElementById('saveBtn').addEventListener('click', function() {
            window.removeEventListener('beforeunload', beforeUnloadHandler);

            let surveyJSON = JSON.parse(creator.text || '{}');

            normalizeScoresInSurvey(surveyJSON);

            const maxScore = calculateMaxScore(surveyJSON);

            if (surveyJSON.max_score !== undefined) delete surveyJSON.max_score;

            document.getElementById('formSurveyJson').value = JSON.stringify(surveyJSON);

            document.getElementById('formMaxScore').value = maxScore;

            document.getElementById('saveForm').submit();

            hasChanges = false;
            initialJson = creator.text;
        });
    </script>
</body>
</html>
