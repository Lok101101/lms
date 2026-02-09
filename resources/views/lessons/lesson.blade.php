<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $lesson->name }}</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f9f9f9;
      padding: 2rem;
      max-width: 930px;
      margin: 0 auto;
    }

    #articleContent {
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div id="articleContent"></div>

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
    import ImageTool from 'https://cdn.skypack.dev/@editorjs/image';
    import SimpleImage from 'https://cdn.skypack.dev/@editorjs/simple-image';

    const lessonData = {!! $lesson->content !!};

    const viewer = new EditorJS({
      holder: 'articleContent',
      readOnly: true,
      data: lessonData,
      tools: {
          header: {
            class: Header
          },
          simpleImage: {
            class: SimpleImage
          },
          checklist: {
            class: Checklist
          },
          list: {
            class: List
          },
          quote: {
            class: Quote
          },
          warning: {
            class: Warning
          },
          marker: {
            class: Marker
          },
          code: {
            class: CodeTool
          },
          delimiter: {
            class: Delimiter
          },
          inlineCode: {
            class: InlineCode
          },
          linkTool: {
            class: LinkTool
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
            class: Table
          }
        }
    });
  </script>
</body>
</html>
