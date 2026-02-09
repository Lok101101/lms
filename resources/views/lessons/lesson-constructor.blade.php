<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Редактор лекции</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('favicon.svg') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .ce-header {
      padding: 1em 0;
      margin: 0;
      line-height: 1.5em;
    }
    h2.ce-header {
      font-size: 1.5em;
      font-weight: bold;
    }
    h3.ce-header {
      font-size: 1.3em;
      font-weight: bold;
    }
    .cdx-marker {
      background: rgba(245, 235, 111, 0.29);
      padding: 3px 0;
    }
  </style>
</head>
<body class="bg-gray-100 p-8 max-w-6xl mx-auto" onload="initEditor()">
  <div class="flex justify-between items-center mb-6 @empty($lesson) flex-col @endempty items-center justify-between sm:flex-row gap-y-2 gap-x-3">
    @isset($lesson)
        <h2 id="lessonTitle" class="text-2xl sm:text-4xl font-bold text-gray-800">{{ $lesson->name }}</h2>
    @else
        <input type="text" id="lessonTitle" placeholder="Название лекции"
             class="w-full px-4 py-2 text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
    @endisset

    <button id="saveBtn" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
      Сохранить
    </button>
  </div>

  <div id="editorjs" class="bg-white p-8 rounded-lg shadow-md min-h-[300px]"></div>

  @isset($lesson)
    <form id="saveForm" method="POST" action="{{ route('changeLesson', $lesson) }}" class="hidden">
      @csrf
      <input type="hidden" name="name" id="formTitle">
      <input type="hidden" name="content" id="formContent">
    </form>
  @else
    <form id="saveForm" method="POST" action="{{ route('createLesson') }}" class="hidden">
      @csrf
      <input type="hidden" name="name" id="formTitle">
      <input type="hidden" name="content" id="formContent">
    </form>
  @endisset

  <script type="module">
    import EditorJS from 'https://cdn.skypack.dev/@editorjs/editorjs';
    import Header from 'https://cdn.skypack.dev/@editorjs/header';
    import Paragraph from 'https://cdn.skypack.dev/@editorjs/paragraph';
    import List from 'https://cdn.skypack.dev/@editorjs/list';
    import Checklist from 'https://cdn.skypack.dev/@editorjs/checklist';
    import Quote from 'https://cdn.skypack.dev/@editorjs/quote';
    import Warning from 'https://cdn.skypack.dev/@editorjs/warning';
    import Marker from 'https://cdn.skypack.dev/@editorjs/marker';
    import CodeTool from 'https://cdn.skypack.dev/@editorjs/code';
    import Delimiter from 'https://cdn.skypack.dev/@editorjs/delimiter';
    import InlineCode from 'https://cdn.skypack.dev/@editorjs/inline-code';
    import LinkTool from 'https://cdn.skypack.dev/@editorjs/link';
    import Embed from 'https://cdn.skypack.dev/@editorjs/embed';
    import Table from 'https://cdn.skypack.dev/@editorjs/table';
    import SimpleImage from 'https://cdn.skypack.dev/@editorjs/simple-image';

    let editor;
    let isDirty = false;

    @isset($lesson)
    const lessonData = {!! $lesson->content !!};
    @endisset

    window.initEditor = () => {
      editor = new EditorJS({
        holder: 'editorjs',
        autofocus: true,
        @isset($lesson) data: lessonData, @endisset
        placeholder: 'Начните писать текст здесь...',
        tools: {
          header: {
            class: Header,
            config: {
              placeholder: 'Введите заголовок',
              levels: [2, 3],
              defaultLevel: 2
            },
          },
          simpleImage: {
            class: SimpleImage
          },
          checklist: {
            class: Checklist
          },
          list: {
            class: List,
            inlineToolbar: true
          },
          quote: {
            class: Quote,
            inlineToolbar: true,
            config: {
              quotePlaceholder: 'Текст цитаты',
              captionPlaceholder: 'Автор'
            }
          },
          warning: {
            class: Warning,
            inlineToolbar: true,
            config: {
              titlePlaceholder: 'Заголовок',
              messagePlaceholder: 'Сообщение'
            }
          },
          marker: {
            class: Marker,
          },
          code: {
            class: CodeTool,
          },
          delimiter: {
            class: Delimiter,
          },
          inlineCode: {
            class: InlineCode,
          },
          linkTool: {
            class: LinkTool,
            config: {
              endpoint: '/fetch-url'
            }
          },
          embed: {
            class: Embed,
            config: {
              services: {
                youtube: true,
                coub: true,
                codepen: true
              }
            }
          },
          table: {
            class: Table,
            inlineToolbar: true
          }
        },
        onReady: () => {
          console.log('EditorJS готов к работе!');
        },
        onChange: () => {
          isDirty = true;
        },
        i18n: {
          messages: {
            ui: {
              blockTunes: {
                toggler: {
                  "Click to tune": "Нажмите, чтобы настроить",
                  "or drag to move": "или перетащите"
                }
              },
              inlineToolbar: {
                converter: {
                  "Convert to": "Конвертировать в"
                }
              },
              toolbar: {
                toolbox: {
                  "Add": "Добавить"
                },
              },
            },
            toolNames: {
              "Text": "Параграф",
              "Heading": "Заголовок",
              "List": "Список",
              "Unordered List": "Неуп. список",
              "Ordered List": "Упоряд. список",
              "Warning": "Примечание",
              "Checklist": "Чеклист",
              "Quote": "Цитата",
              "Code": "Код",
              "Delimiter": "Разделитель",
              "Raw HTML": "HTML-фрагмент",
              "Table": "Таблица",
              "Link": "Ссылка",
              "Marker": "Маркер",
              "Bold": "Полужирный",
              "Italic": "Курсив",
              "InlineCode": "Моноширинный",
              "Image": "Изображение"
            },
            tools: {
              warning: {
                "Title": "Название",
                "Message": "Сообщение",
              },
              link: {
                "Add a link": "Вставьте ссылку"
              },
              stub: {
                "The block can not be displayed correctly.": "Блок не может быть отображен"
              },
              header: {
                "Header": "Заголовок",
                "Heading": "Заголовок",
                "Enter a header": "Введите заголовок"
              },
              list: {
                // "Ordered": "Нумерованный",
                // "Unordered": "Маркированный",
                // "Ordered List": "Нумерованный список",
                // "Unordered List": "Маркированный список"
              },
              image: {
                "Caption": "Подпись",
                "Select an Image": "Выберите изображение",
                "With border": "С рамкой",
                "Stretch image": "Растянуть",
                "With background": "С фоном",
                "Add image": "Добавить изображение",
                "Add image via URL": "Добавить по ссылке"
              },
              table: {
                "Table": "Таблица",
                "Add column to left": "Добавить столбец слева",
                "Add column to right": "Добавить столбец справа",
                "Delete column": "Удалить столбец",
                "Add row above": "Добавить строку выше",
                "Add row below": "Добавить строку ниже",
                "Delete row": "Удалить строку",
                "With headings": "С заголовками",
                "Without headings": "Без заголовков"
              }
            },
            blockTunes: {
              delete: {
                "Delete": "Удалить",
                "Click to delete": "Нажмите для удаления"
              },
              moveUp: {
                "Move up": "Переместить вверх",
                "Click to move up": "Нажмите для перемещения вверх"
              },
              moveDown: {
                "Move down": "Переместить вниз",
                "Click to move down": "Нажмите для перемещения вниз"
              }
            }
          }
        }
      });

      document.getElementById('saveBtn').addEventListener('click', async () => {
        @isset($lesson)
        const title = document.getElementById('lessonTitle').textContent
        @else
        const title = document.getElementById('lessonTitle').value;
        @endisset
        if (!title) {
          alert("Введите название статьи");
          return;
        }

        try {
          const output = await editor.save();
          document.getElementById('formTitle').value = title;
          document.getElementById('formContent').value = JSON.stringify(output);
          isDirty = false;
          document.getElementById('saveForm').submit();
        } catch (e) {
          alert('Ошибка при сохранении. Проверьте правильность заполнения.');
          console.error(e);
        }
      });

      window.addEventListener('beforeunload', (e) => {
        if (isDirty) {
          e.preventDefault();
          e.returnValue = '';
        }
      });
    };
  </script>
</body>
</html>
