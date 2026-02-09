<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestsSeeder extends Seeder
{
    /**
     * Calculate maximum score for a test
     */
    private function calculateMaxScore(array $content): int
    {
        $data = json_decode($content, true);
        $totalScore = 0;

        foreach ($data['pages'] as $page) {
            foreach ($page['elements'] as $question) {
                if ($question['type'] === 'radiogroup' || $question['type'] === 'dropdown') {
                    $totalScore += 10;
                } elseif ($question['type'] === 'checkbox') {
                    $totalScore += 10;
                } elseif ($question['type'] === 'matrix') {
                    $rows = count($question['rows']);
                    $totalScore += min($rows * 3.33, 10);
                }
            }
        }

        return (int) round($totalScore);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherId = User::where('name', '=', 'teacher')->first()->id;

        $tests = [
            [
                'name' => 'Что такое Laravel?',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Что такое Laravel?",
          "choices": [
            {"value": "Язык программирования", "score": 0},
            {"value": "PHP-фреймворк", "score": 10},
            {"value": "Система управления базами данных", "score": 0},
            {"value": "Графический редактор", "score": 0}
          ],
          "correctAnswer": "PHP-фреймворк"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие преимущества Laravel?",
          "choices": [
            {"value": "Eloquent ORM", "score": 2.5},
            {"value": "Система маршрутизации", "score": 2.5},
            {"value": "Шаблонизатор Blade", "score": 2.5},
            {"value": "Встроенная аутентификация", "score": 2.5}
          ],
          "maxSelectedChoices": 4,
          "correctAnswer": [
            "Eloquent ORM",
            "Система маршрутизации",
            "Шаблонизатор Blade",
            "Встроенная аутентификация"
          ]
        },
        {
          "name": "question4",
          "type": "radiogroup",
          "title": "Какой инструмент используется для управления зависимостями в Laravel?",
          "choices": [
            {"value": "npm", "score": 0},
            {"value": "Composer", "score": 10},
            {"value": "yarn", "score": 0},
            {"value": "pip", "score": 0}
          ],
          "correctAnswer": "Composer"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Что такое Laravel?'",
  "description": "Проверка знаний по основам Laravel",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Маршрутизация и контроллеры',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Где определяются веб-маршруты в Laravel?",
          "choices": [
            {"value": "routes/api.php", "score": 0},
            {"value": "routes/web.php", "score": 10},
            {"value": "app/Http/Controllers", "score": 0},
            {"value": "config/routes.php", "score": 0}
          ],
          "correctAnswer": "routes/web.php"
        },
        {
          "name": "question2",
          "type": "dropdown",
          "title": "Какой командой создать ресурсный контроллер?",
          "choices": [
            {"value": "php artisan make:controller --resource", "score": 10},
            {"value": "php artisan make:model --controller", "score": 0},
            {"value": "php artisan make:resource", "score": 0},
            {"value": "php artisan controller:make", "score": 0}
          ],
          "correctAnswer": "php artisan make:controller --resource"
        },
        {
          "name": "question3",
          "type": "checkbox",
          "title": "Какие методы HTTP поддерживает Route::resource?",
          "choices": [
            {"value": "index", "score": 1.43},
            {"value": "create", "score": 1.43},
            {"value": "store", "score": 1.43},
            {"value": "show", "score": 1.43},
            {"value": "edit", "score": 1.43},
            {"value": "update", "score": 1.43},
            {"value": "destroy", "score": 1.43}
          ],
          "maxSelectedChoices": 7,
          "correctAnswer": [
            "index",
            "create",
            "store",
            "show",
            "edit",
            "update",
            "destroy"
          ]
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Маршрутизация и контроллеры'",
  "description": "Проверка знаний по маршрутизации и контроллерам в Laravel",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Eloquent ORM',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Что такое Eloquent ORM?",
          "choices": [
            {"value": "Система кэширования", "score": 0},
            {"value": "Реализация шаблона Active Record", "score": 10},
            {"value": "Шаблонизатор", "score": 0},
            {"value": "Система очередей", "score": 0}
          ],
          "correctAnswer": "Реализация шаблона Active Record"
        },
        {
          "name": "question3",
          "rows": [
            {"value": "Один к одному", "score": 3.33},
            {"value": "Один ко многим", "score": 3.33},
            {"value": "Многие ко многим", "score": 3.33}
          ],
          "type": "matrix",
          "title": "Соответствие методов отношениям",
          "columns": [
            {"value": "hasOne", "score": 3.33},
            {"value": "hasMany", "score": 3.33},
            {"value": "belongsToMany", "score": 3.33}
          ],
          "correctAnswer": {
            "Один к одному": "hasOne",
            "Один ко многим": "hasMany",
            "Многие ко многим": "belongsToMany"
          }
        },
        {
          "name": "question4",
          "type": "checkbox",
          "title": "Какие возможности предоставляет Eloquent?",
          "choices": [
            {"value": "Мутаторы и аксессоры", "score": 2.5},
            {"value": "Мягкое удаление", "score": 2.5},
            {"value": "События моделей", "score": 2.5},
            {"value": "Ленивая загрузка", "score": 2.5}
          ],
          "maxSelectedChoices": 4,
          "correctAnswer": [
            "Мутаторы и аксессоры",
            "Мягкое удаление",
            "События моделей",
            "Ленивая загрузка"
          ]
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Погружение в Eloquent ORM'",
  "description": "Проверка знаний по работе с Eloquent ORM",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Введение в JavaScript: история и назначение',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "В каком году был создан JavaScript?",
          "choices": [
            {"value": "1995", "score": 10},
            {"value": "1999", "score": 0},
            {"value": "2005", "score": 0},
            {"value": "2010", "score": 0}
          ],
          "correctAnswer": "1995"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Где применяется JavaScript?",
          "choices": [
            {"value": "В браузерах", "score": 3.33},
            {"value": "Серверная разработка (Node.js)", "score": 3.33},
            {"value": "Мобильные приложения", "score": 3.33},
            {"value": "Операционные системы", "score": 0}
          ],
          "maxSelectedChoices": 3,
          "correctAnswer": [
            "В браузерах",
            "Серверная разработка (Node.js)",
            "Мобильные приложения"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Кто создал JavaScript?",
          "choices": [
            {"value": "Брендан Айк", "score": 10},
            {"value": "Гвидо ван Россум", "score": 0},
            {"value": "Деннис Ритчи", "score": 0},
            {"value": "Джеймс Гослинг", "score": 0}
          ],
          "correctAnswer": "Брендан Айк"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Введение в JavaScript'",
  "description": "Проверка знаний по истории и назначению JavaScript",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Основы синтаксиса JavaScript',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "checkbox",
          "title": "Какие способы объявления переменных существуют в JavaScript?",
          "choices": [
            {"value": "var", "score": 3.33},
            {"value": "let", "score": 3.33},
            {"value": "const", "score": 3.33},
            {"value": "static", "score": 0}
          ],
          "maxSelectedChoices": 3,
          "correctAnswer": ["var", "let", "const"]
        },
        {
          "name": "question2",
          "type": "radiogroup",
          "title": "Как обозначается однострочный комментарий?",
          "choices": [
            {"value": "//", "score": 10},
            {"value": "/*", "score": 0},
            {"value": "<!--", "score": 0},
            {"value": "#", "score": 0}
          ],
          "correctAnswer": "//"
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Какая функция создает новую переменную в области видимости блока?",
          "choices": [
            {"value": "var", "score": 0},
            {"value": "let", "score": 10},
            {"value": "const", "score": 0},
            {"value": "function", "score": 0}
          ],
          "correctAnswer": "let"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Основы синтаксиса JavaScript'",
  "description": "Проверка знаний базового синтаксиса и объявления переменных",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Переменные, типы данных и преобразования в JavaScript',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "checkbox",
          "title": "Какие типы данных есть в JavaScript?",
          "choices": [
            {"value": "string", "score": 2},
            {"value": "number", "score": 2},
            {"value": "boolean", "score": 2},
            {"value": "object", "score": 2},
            {"value": "undefined", "score": 2},
            {"value": "list", "score": 0}
          ],
          "maxSelectedChoices": 5,
          "correctAnswer": [
            "string",
            "number",
            "boolean",
            "object",
            "undefined"
          ]
        },
        {
          "name": "question2",
          "type": "radiogroup",
          "title": "Что вернет выражение typeof null?",
          "choices": [
            {"value": "\"object\"", "score": 10},
            {"value": "\"null\"", "score": 0},
            {"value": "\"undefined\"", "score": 0},
            {"value": "\"boolean\"", "score": 0}
          ],
          "correctAnswer": "\"object\""
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Переменные, типы данных и преобразования'",
  "description": "Проверка знаний по переменным и типам данных в JavaScript",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Введение в PHP: история и применение',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Кто создал PHP?",
          "choices": [
            {"value": "Брендан Айк", "score": 0},
            {"value": "Расмус Лердорф", "score": 10},
            {"value": "Гвидо ван Россум", "score": 0},
            {"value": "Джеймс Гослинг", "score": 0}
          ],
          "correctAnswer": "Расмус Лердорф"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Где используется PHP?",
          "choices": [
            {"value": "Создание динамических веб-сайтов", "score": 3.33},
            {"value": "Работа с базами данных", "score": 3.33},
            {"value": "Мобильная разработка приложений", "score": 0},
            {"value": "Создание REST API", "score": 3.33}
          ],
          "maxSelectedChoices": 3,
          "correctAnswer": [
            "Создание динамических веб-сайтов",
            "Работа с базами данных",
            "Создание REST API"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "В каком году появился PHP?",
          "choices": [
            {"value": "1995", "score": 10},
            {"value": "2005", "score": 0},
            {"value": "2010", "score": 0},
            {"value": "1985", "score": 0}
          ],
          "correctAnswer": "1995"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Введение в PHP'",
  "description": "Проверка знаний по истории и применению PHP",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Основы синтаксиса PHP',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "С какого тега начинается PHP-скрипт?",
          "choices": [
            {"value": "<?php", "score": 10},
            {"value": "<script>", "score": 0},
            {"value": "<php>", "score": 0},
            {"value": "<code>", "score": 0}
          ],
          "correctAnswer": "<?php"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие типы данных поддерживает PHP?",
          "choices": [
            {"value": "integer", "score": 2.5},
            {"value": "float", "score": 2.5},
            {"value": "string", "score": 2.5},
            {"value": "array", "score": 2.5},
            {"value": "dictionary", "score": 0}
          ],
          "maxSelectedChoices": 4,
          "correctAnswer": [
            "integer",
            "float",
            "string",
            "array"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Чем заканчивается каждая инструкция в PHP?",
          "choices": [
            {"value": "Точкой с запятой", "score": 10},
            {"value": "Двоеточием", "score": 0},
            {"value": "Запятой", "score": 0},
            {"value": "Точкой", "score": 0}
          ],
          "correctAnswer": "Точкой с запятой"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Основы синтаксиса PHP'",
  "description": "Проверка знаний по базовому синтаксису PHP",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Управляющие конструкции и функции в PHP',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Какой цикл используется для обхода массивов?",
          "choices": [
            {"value": "for", "score": 0},
            {"value": "foreach", "score": 10},
            {"value": "while", "score": 0},
            {"value": "do-while", "score": 0}
          ],
          "correctAnswer": "foreach"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие управляющие конструкции есть в PHP?",
          "choices": [
            {"value": "if", "score": 2.5},
            {"value": "else", "score": 2.5},
            {"value": "switch", "score": 2.5},
            {"value": "repeat", "score": 0},
            {"value": "for", "score": 2.5}
          ],
          "maxSelectedChoices": 4,
          "correctAnswer": [
            "if",
            "else",
            "switch",
            "for"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "С помощью какой инструкции объявляется функция в PHP?",
          "choices": [
            {"value": "function", "score": 10},
            {"value": "def", "score": 0},
            {"value": "func", "score": 0},
            {"value": "method", "score": 0}
          ],
          "correctAnswer": "function"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Управляющие конструкции и функции в PHP'",
  "description": "Проверка знаний по управляющим конструкциям и функциям в PHP",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Введение в SQL: история и назначение',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Для чего применяется SQL?",
          "choices": [
            {"value": "Работа с реляционными базами данных", "score": 10},
            {"value": "Создание сайтов", "score": 0},
            {"value": "Машинное обучение", "score": 0},
            {"value": "Дизайн интерфейсов", "score": 0}
          ],
          "correctAnswer": "Работа с реляционными базами данных"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие СУБД поддерживают SQL?",
          "choices": [
            {"value": "MySQL", "score": 3.33},
            {"value": "PostgreSQL", "score": 3.33},
            {"value": "MongoDB", "score": 0},
            {"value": "Oracle", "score": 3.33}
          ],
          "maxSelectedChoices": 3,
          "correctAnswer": [
            "MySQL",
            "PostgreSQL",
            "Oracle"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Когда появился SQL?",
          "choices": [
            {"value": "1970-е", "score": 10},
            {"value": "1990-е", "score": 0},
            {"value": "2000-е", "score": 0},
            {"value": "1960-е", "score": 0}
          ],
          "correctAnswer": "1970-е"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Введение в SQL: история и назначение'",
  "description": "Проверка знаний по истории и назначению SQL",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Основные команды SELECT, INSERT, UPDATE, DELETE',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Какой SQL-запрос используется для добавления данных?",
          "choices": [
            {"value": "INSERT", "score": 10},
            {"value": "SELECT", "score": 0},
            {"value": "UPDATE", "score": 0},
            {"value": "DELETE", "score": 0}
          ],
          "correctAnswer": "INSERT"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие команды относятся к DML (Data Manipulation Language)?",
          "choices": [
            {"value": "SELECT", "score": 2.5},
            {"value": "INSERT", "score": 2.5},
            {"value": "ALTER", "score": 0},
            {"value": "UPDATE", "score": 2.5},
            {"value": "DELETE", "score": 2.5}
          ],
          "maxSelectedChoices": 4,
          "correctAnswer": [
            "SELECT",
            "INSERT",
            "UPDATE",
            "DELETE"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Что делает команда DELETE?",
          "choices": [
            {"value": "Удаляет строки", "score": 10},
            {"value": "Удаляет столбцы", "score": 0},
            {"value": "Удаляет таблицу", "score": 0},
            {"value": "Создаёт таблицу", "score": 0}
          ],
          "correctAnswer": "Удаляет строки"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Основные команды SELECT, INSERT, UPDATE, DELETE'",
  "description": "Проверка знаний по основным SQL-командам",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Создание и изменение таблиц в SQL',
                'user_id' => $teacherId,
                'content' => <<<JSON
{
  "pages": [
    {
      "name": "page1",
      "elements": [
        {
          "name": "question1",
          "type": "radiogroup",
          "title": "Какая команда используется для создания новой таблицы?",
          "choices": [
            {"value": "CREATE TABLE", "score": 10},
            {"value": "ALTER TABLE", "score": 0},
            {"value": "DROP TABLE", "score": 0},
            {"value": "INSERT INTO", "score": 0}
          ],
          "correctAnswer": "CREATE TABLE"
        },
        {
          "name": "question2",
          "type": "checkbox",
          "title": "Какие действия можно выполнить с помощью ALTER TABLE?",
          "choices": [
            {"value": "Добавить столбец", "score": 3.33},
            {"value": "Удалить столбец", "score": 3.33},
            {"value": "Изменить тип столбца", "score": 3.33},
            {"value": "Удалить строку", "score": 0}
          ],
          "maxSelectedChoices": 3,
          "correctAnswer": [
            "Добавить столбец",
            "Удалить столбец",
            "Изменить тип столбца"
          ]
        },
        {
          "name": "question3",
          "type": "radiogroup",
          "title": "Как удалить столбец age из таблицы users?",
          "choices": [
            {"value": "ALTER TABLE users DROP COLUMN age;", "score": 10},
            {"value": "DELETE FROM users WHERE age;", "score": 0},
            {"value": "DROP TABLE users;", "score": 0},
            {"value": "ALTER TABLE users REMOVE age;", "score": 0}
          ],
          "correctAnswer": "ALTER TABLE users DROP COLUMN age;"
        }
      ]
    }
  ],
  "title": "Тест по лекции 'Создание и изменение таблиц в SQL'",
  "description": "Проверка знаний по созданию и изменению таблиц в SQL",
  "completedHtml": "<h3>Спасибо за прохождение теста!</h3>",
  "showQuestionNumbers": "on"
}
JSON
                ,
                'max_score' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('tests')->insert($tests);
    }
}
