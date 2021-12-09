<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset("/assets/grapes/grapes.min.css") }}">
        <script src="{{ asset("/assets/grapes/grapes.min.js") }}"></script>
	</head>
	<body style="margin:0;">
        <div id="gjs" style="height:0px; overflow:hidden;">
          <div class="panel">
            <h1>Hello World</h1>
        </div>
    
        <script type="text/javascript">
          if(!window.parent.__code__) {
            throw "Code not found";
          }
          const editor = grapesjs.init({
            showOffsets: 1,
            noticeOnUnload: 0,
            container: '#gjs',
            height: '100%',
            fromElement: true,
            storageManager: { autoload: 0 },
            styleManager : {
              sectors: [{
                  name: 'General',
                  open: false,
                  buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom']
                },{
                  name: 'Flex',
                  open: false,
                  buildProps: ['flex-direction', 'flex-wrap', 'justify-content', 'align-items', 'align-content', 'order', 'flex-basis', 'flex-grow', 'flex-shrink', 'align-self']
                },{
                  name: 'Dimension',
                  open: false,
                  buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                },{
                  name: 'Typography',
                  open: false,
                  buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-shadow'],
                },{
                  name: 'Decorations',
                  open: false,
                  buildProps: ['border-radius-c', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
                },{
                  name: 'Extra',
                  open: false,
                  buildProps: ['transition', 'perspective', 'transform'],
                }
              ],
            },
          });
    
          editor.BlockManager.add('testBlock', {
            label: 'Block',
            attributes: { class:'gjs-fonts gjs-f-b1' },
            content: `<div style="padding-top:50px; padding-bottom:50px; text-align:center">Test block</div>`
          })
        </script>
      </body>
</html>
