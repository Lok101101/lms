<!DOCTYPE html>
<html>
<head>
    <title>Конструктор теста</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link href="https://unpkg.com/survey-core/survey-core.min.css" type="text/css" rel="stylesheet">
    <script src="https://unpkg.com/survey-core/survey.core.min.js"></script>
    <script src="https://unpkg.com/survey-js-ui/survey-js-ui.min.js"></script>
    <link href="https://unpkg.com/survey-creator-core/survey-creator-core.min.css" type="text/css" rel="stylesheet">
    <script src="https://unpkg.com/survey-creator-core/survey-creator-core.min.js"></script>
    <script src="https://unpkg.com/survey-creator-js/survey-creator-js.min.js"></script>
    <script src="https://unpkg.com/survey-creator-core/survey-creator-core.i18n.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .svc-creator__non-commercial-text { display: none; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-gray-50">
    <div class="header bg-gray-100 p-3 sm:p-4 border-b border-gray-200">
        <div class="flex items-center justify-between flex-col sm:flex-row gap-y-2 gap-x-3">
            <input type="text" id="surveyName" placeholder="Название теста" class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-transparent">
            <button id="saveBtn" class="px-3 py-2 bg-[#4CAF50] text-white rounded-lg hover:bg-[#45a049] transition-colors">Сохранить</button>
        </div>
    </div>

    <div id="surveyCreator" class="h-[calc(100vh-72px)]"></div>

    <form id="saveForm" method="POST" action="{{ route('createTest') }}" class="hidden">
        @csrf
        <input type="hidden" name="name" id="formSurveyName">
        <input type="hidden" name="content" id="formSurveyJson">
    </form>

    <script>
        Survey.Serializer.addProperty("itemvalue", { name: "score:number" });

        SurveyCreator.localization.currentLocale = "ru";
        SurveyCreator.localization.locales["ru"].pe.clear = "Очистить";
        SurveyCreator.localization.locales["ru"].pe.change = "Поменять";
        SurveyCreator.localization.locales["ru"].pe.set = "Установить";
        SurveyCreator.localization.locales["ru"].pe.score = "Баллы";

        const creator = new SurveyCreator.SurveyCreator({ showLogicTab: true, isAutoSave: false });
        creator.render("surveyCreator");

        let isSavedToServer = false;
        let initialSurveyJson = JSON.stringify(creator.JSON);

        function hasUnsavedChanges() {
            return JSON.stringify(creator.JSON) !== initialSurveyJson;
        }

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

        window.addEventListener('beforeunload', e => {
            if (hasUnsavedChanges() && !isSavedToServer) {
                e.preventDefault();
                e.returnValue = 'У вас есть несохраненные изменения. Вы уверены, что хотите уйти?';
            }
        });

        document.getElementById('saveBtn').addEventListener('click', () => {
            const surveyName = document.getElementById('surveyName').value;
            if (!surveyName) { alert("Введите название теста для сохранения"); return; }

            let surveyJSON = JSON.parse(creator.text);

            normalizeScoresInSurvey(surveyJSON);

            const maxScore = calculateMaxScore(surveyJSON);

            if (surveyJSON.max_score !== undefined) delete surveyJSON.max_score;

            document.getElementById('formSurveyName').value = surveyName;
            document.getElementById('formSurveyJson').value = JSON.stringify(surveyJSON);

            let maxScoreInput = document.getElementById('formMaxScore');
            if (!maxScoreInput) {
                maxScoreInput = document.createElement('input');
                maxScoreInput.type = 'hidden';
                maxScoreInput.name = 'max_score';
                maxScoreInput.id = 'formMaxScore';
                document.getElementById('saveForm').appendChild(maxScoreInput);
            }
            maxScoreInput.value = maxScore;

            document.getElementById('saveForm').submit();
            isSavedToServer = true;
            initialSurveyJson = JSON.stringify(creator.JSON);
        });
    </script>
</body>
</html>
