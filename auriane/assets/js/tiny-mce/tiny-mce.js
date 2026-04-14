(function(){
  tinymce.create('tinymce.plugins.MyPluginName', {
    init: function(ed, url){
      ed.addButton('myaccordionbtn', {
        title: 'Accordion',
        cmd: 'myaccordionBtnCmd',
        image: jws_script.theme_path+'/assets/image/accordion-menu.svg',
      });
      ed.addCommand('myaccordionBtnCmd', function(){
        var selectedText = ed.selection.getContent({format: 'html'});
        var win = ed.windowManager.open({
          title: 'Accordion Text',
          body: [
            {
              type: 'textbox',
              name: 'accordion_text',
              label: 'Title Accordion',
              minWidth: 500,
              value: ''
            },

          ],
          buttons: [
            {
              text: "Ok",
              subtype: "primary",
              onclick: function() {
                win.submit();
              }
            },
            {
              text: "Skip",
              onclick: function() {
                win.close();
                var returnText = '<div class="accordion">' + selectedText + '</div>';
                ed.execCommand('mceInsertContent', 0, returnText);
              }
            },
            {
              text: "Cancel",
              onclick: function() {
                win.close();
              }
            }
          ],
          onsubmit: function(e){
            var params = [];
            if( e.data.accordion_text.length > 0 ) {
              params.push(e.data.accordion_text);
            }

            if( params.length > 0 ) {
              paramsString = ' ' + params.join(' ');
            }
            var returnText = '<div style="padding: 10px 25px;background: #404040;color: #ffffff; position: relative; " class="accordion">' + paramsString + '<span></span></div>';
            ed.execCommand('mceInsertContent', 0, returnText);
          }
        });
      });
    },

  });
  tinymce.PluginManager.add( 'myaccordionbtn', tinymce.plugins.MyPluginName );
})();