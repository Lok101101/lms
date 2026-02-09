<!DOCTYPE html>
<html>
<head>
    <title>{{ $test->name }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/survey-core/defaultV2.min.css" rel="stylesheet">
    <link href="https://unpkg.com/survey-core/survey-core.min.css" rel="stylesheet">
    <script src="https://unpkg.com/survey-core/survey.core.min.js"></script>
    <script src="https://unpkg.com/survey-js-ui/survey-js-ui.min.js"></script>
    <script src="https://unpkg.com/survey-core/survey.i18n.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fa; margin: 0; padding: 20px; }
        #testContainer { max-width: 800px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); overflow: hidden; }
        #surveyContainer { padding: 30px; }
        #resultsContainer { display: none; padding: 30px; }
        .result-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .result-score { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .progress-container { margin: 30px 0; }
        .progress-bar { height: 10px; background-color: #e0e0e0; border-radius: 5px; overflow: hidden; }
        .progress { height: 100%; background-color: #4CAF50; width: 0%; transition: width 0.5s ease; }
        .question-result { margin-bottom: 25px; padding: 20px; background-color: #f8f9fa; border-radius: 8px; }
        .question-text { font-weight: bold; margin-bottom: 15px; color: #2c3e50; }
        .user-answer.correct { color: #4CAF50; font-weight: bold; }
        .user-answer.incorrect { color: #f44336; text-decoration: line-through; }
        .correct-answer { color: #4CAF50; font-style: italic; margin-top: 5px; }
        .result-grade { text-align: center; font-size: 22px; margin: 15px 0; padding: 10px; border-radius: 8px; }
        .excellent { background-color: #4CAF50; color: white; }
        .good { background-color: #8BC34A; color: white; }
        .satisfactory { background-color: #FFC107; color: #333; }
        .poor { background-color: #FF9800; color: white; }
        .fail { background-color: #F44336; color: white; }
    </style>
</head>
<body>
    <div id="testContainer">
        <div id="surveyContainer"></div>
        <div id="resultsContainer">
            <div class="result-header">
                <h2>Результаты теста</h2>
            </div>
            <div class="result-score">
                Баллов: <span id="score">0</span> из <span id="total">0</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress" id="progressBar"></div>
                </div>
            </div>
            <form action="{{ route('completeTest', $test) }}" method="POST" class="flex justify-center">
                @csrf
                <input type="hidden" value="" name="estimation" id="estimation">
                <input type="hidden" value="" name="score" id="scoreField">
                <button type="submit" class="px-5 py-2.5 bg-[#17b292] text-white rounded-lg hover:bg-[#11957a] transition-colors flex items-center justify-center font-bold text-2xl w-50 cursor-pointer">Завершить</button>
            </form>
        </div>
    </div>

    <script>
    const surveyJson = JSON.parse(@json($test->content ?? '{}'));

    surveyJson.showProgressBar = 'bottom';
    surveyJson.progressBarType = 'questions';
    surveyJson.completeText = 'Завершить';

    Survey.Serializer.addProperty("itemvalue", { name: "score:number" });

    function normalizeSurveyJson(sj) {
        if (!sj || !Array.isArray(sj.pages)) return;
        sj.pages.forEach(page => {
            if (!Array.isArray(page.elements)) return;
            page.elements.forEach(q => {
                ['choices', 'rateValues', 'columns'].forEach(key => {
                    if (Array.isArray(q[key])) {
                        q[key].forEach((c, idx) => {
                            if (typeof c === 'string') {
                                q[key][idx] = { value: c, text: c, score: 0 };
                            } else if (typeof c === 'object' && c !== null) {
                                if (c.value === undefined && c.text !== undefined) c.value = c.text;
                                if (c.text === undefined && c.value !== undefined) c.text = c.value;
                                if (c.score === undefined) c.score = 0;
                            } else {
                                q[key][idx] = { value: String(c), text: String(c), score: 0 };
                            }
                        });
                    }
                });

                if (q.type === 'matrixdropdown' && Array.isArray(q.columns)) {
                    q.columns.forEach(col => {
                        if (Array.isArray(col.choices)) {
                            col.choices.forEach((c, idx) => {
                                if (typeof c === 'string') col.choices[idx] = { value: c, text: c, score: 0 };
                                else if (typeof c === 'object' && c !== null) {
                                    if (c.value === undefined && c.text !== undefined) c.value = c.text;
                                    if (c.text === undefined && c.value !== undefined) c.text = c.value;
                                    if (c.score === undefined) c.score = 0;
                                } else {
                                    col.choices[idx] = { value: String(c), text: String(c), score: 0 };
                                }
                            });
                        }
                    });
                }

                if (q.type === 'boolean' && q.score === undefined) q.score = 1;

                if (Array.isArray(q.items)) {
                    q.items.forEach((c, idx) => {
                        if (typeof c === 'string') q.items[idx] = { value: c, text: c, score: 0 };
                        else if (typeof c === 'object' && c !== null && c.score === undefined) c.score = 0;
                    });
                }
            });
        });
    }

    normalizeSurveyJson(surveyJson);

    const scoreMap = {};
    if (Array.isArray(surveyJson.pages)) {
        surveyJson.pages.forEach(page => {
            if (!Array.isArray(page.elements)) return;
            page.elements.forEach(q => {
                const qName = q.name;
                if (!qName) return;
                const entry = { type: q.type || null, choices: {}, booleanScore: null, correctAnswer: q.correctAnswer, matrixCorrectAnswer: q.correctAnswer };
                ['choices', 'rateValues', 'columns', 'items'].forEach(key => {
                    if (Array.isArray(q[key])) {
                        q[key].forEach(c => {
                            const val = (c && c.value != null) ? String(c.value) : (c && c.text != null ? String(c.text) : null);
                            const sc = Number(c && c.score != null ? c.score : 0);
                            if (val !== null) entry.choices[val] = sc;
                        });
                    }
                });
                if (q.type === 'boolean') {
                    entry.booleanScore = Number(q.score != null ? q.score : 1); // default 1
                }
                scoreMap[qName] = entry;
            });
        });
    }

    const survey = new Survey.Model(surveyJson);
    survey.locale = "ru";

    survey.onComplete.add((sender) => {
        document.getElementById("surveyContainer").style.display = "none";
        document.getElementById("resultsContainer").style.display = "block";

        const questions = sender.getAllQuestions();
        let totalScore = 0;
        const maxScore = Number({{ $test->max_score ?? 0 }}) || 0;
        document.getElementById("total").textContent = maxScore;

        questions.forEach(q => {
            const name = q.name;
            if (!name) return;
            const map = scoreMap[name] || {};
            const type = map.type || q.getType();

            const val = q.value;

            if (val == null) return;

            if (type === 'checkbox') {
                (val || []).forEach(v => {
                    const key = String(v);
                    totalScore += Number(map.choices[key] ?? 0);
                });
            } else if (type === 'radiogroup' || type === 'dropdown') {
                const key = String(val);
                totalScore += Number(map.choices[key] ?? 0);
            } else if (type === 'matrix') {
                const correct = map.matrixCorrectAnswer || {};
                Object.entries(correct).forEach(([rowKey, correctCol]) => {
                    if (q.value && q.value[rowKey] === correctCol) totalScore += 1;
                });
            } else if (type === 'boolean') {
                const userVal = (val === true || val === 'true' || val === 1 || val === '1');
                const correctVal = (map.correctAnswer === true || map.correctAnswer === 'true' || map.correctAnswer === 1 || map.correctAnswer === '1');
                if (userVal === correctVal) {
                    totalScore += Number(map.booleanScore ?? 0);
                }
            } else {
                if (typeof q.isAnswerCorrect === 'function' && q.isAnswerCorrect()) totalScore += 1;
            }
        });

        totalScore = Number(totalScore) || 0;
        document.getElementById("score").textContent = totalScore;

        document.getElementById("scoreField").value = totalScore;

        const percentage = maxScore > 0 ? (totalScore / maxScore) * 100 : 0;
        document.getElementById("progressBar").style.width = percentage + "%";

        let grade = '', gradeClass = '', estimation = 0;
        if (percentage >= 90) { estimation = 5; grade = '5 (Отлично)'; gradeClass = 'excellent'; }
        else if (percentage >= 75) { estimation = 4; grade = '4 (Хорошо)'; gradeClass = 'good'; }
        else if (percentage >= 30) { estimation = 3; grade = '3 (Удовлетворительно)'; gradeClass = 'satisfactory'; }
        else { estimation = 2; grade = '2 (Неудовлетворительно)'; gradeClass = 'poor'; }

        document.getElementById('estimation').value = estimation;

        const gradeElement = document.createElement('div');
        gradeElement.className = `result-grade ${gradeClass}`;
        gradeElement.innerHTML = `<strong>Ваша оценка:</strong> ${grade} (${Math.round(percentage)}%)`;
        document.querySelector('.result-score').after(gradeElement);
    });

    document.addEventListener("DOMContentLoaded", () => {
        survey.render(document.getElementById("surveyContainer"));
    });
    </script>
</body>
</html>
