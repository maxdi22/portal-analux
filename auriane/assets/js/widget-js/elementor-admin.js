/**
 * jws Plugin - jws Elementor Admin
 */
jQuery( document ).ready( function ( $ )
{
    'use strict';

    var jwsElementorAdmin = {
        init: function ()
        {
            jwsElementorAdmin.initCustomCSS();


            elementor.on('panel:init', function() {
                elementor.panel.currentView.on('set:page', jwsElementorAdmin.panelChange);
                elementor.channels.editor.on('section:activated', jwsElementorAdmin.removeControls);
            });

        },
        initCustomCSS: function ()
        {
            // custom page css
            var custom_css = elementor.settings.page.model.get( 'page_css' );

            setTimeout( function ()
            {
                typeof custom_css != 'undefined' && elementorFrontend.hooks.doAction( 'refresh_page_css', custom_css );
            }, 1000 );

            $( document.body ).on( 'input', 'textarea[data-setting="page_css"]', function ( e )
            {
                if ( $(this).closest('.elementor-control').siblings('.elementor-control-_jws_custom_css').length ) {
                    elementor.settings.page.model.set( 'page_css', $(this).val() );

                    $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
                    $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
                }

                elementorFrontend.hooks.doAction( 'refresh_page_css', $( this ).val() );
            } )
        },

        getValUnit: function ( $arr, $default )
        {
            if ( $arr ) {
                if ( $arr['size'] ) {
                    return $arr[ 'size' ] + ( $arr[ 'unit' ] ? $arr[ 'unit' ] : 'px' );
                } else {
                    return '';
                }
            }

            return typeof $default == 'undefined' ? '' : $default;
        },
        panelChange: function(panel) {
     
            if ( "_jws_section_custom_css" == panel.activeSection ) {
                var oldName = panel.activeSection.replaceAll('_section', ''),
                    newName = oldName.replaceAll('_jws_custom', 'page');

                if ( $('.elementor-control-' + newName).length ) {
                    return;
                }
   console.log(elementor.settings.page.model.get( newName ));
                var $newControl = $('.elementor-control-' + oldName).clone().removeClass('elementor-control-' + oldName).addClass('elementor-control-' + newName);

                $newControl.insertAfter($('.elementor-control-' + oldName));
                $newControl.find('textarea').attr('data-setting', newName).val(elementor.settings.page.model.get( newName ));

                  if ( newName == 'page_css' ) {
                    $('.elementor-control-page_js').remove();
                } else {
                    $('.elementor-control-page_css').remove();
                }
          
            }else if ( "jws_custom_css_settings" == panel.activeSection ) {
                $('.elementor-control-page_css').val(elementor.settings.page.model.get( 'page_css' ));
            }
      
        },
        
        removeControls: function(activeSection) {
            if ( "_jws_section_custom_css" != activeSection ) {
                $('.elementor-control-page_css').remove();
            } else {
                var oldName = activeSection.replaceAll('_section', ''),
                    newName = oldName.replaceAll('_jws_custom', 'page'),
                    $newControl = $('.elementor-control-' + oldName).clone().removeClass('elementor-control-' + oldName).addClass('elementor-control-' + newName);

                $newControl.insertAfter($('.elementor-control-' + oldName));
                $newControl.find('textarea').attr('data-setting', newName).val(elementor.settings.page.model.get( newName ));
 if ( newName == 'page_css' ) {
                    $('.elementor-control-page_js').remove();
                } else {
                    $('.elementor-control-page_css').remove();
                }
              
               
            }
        }
    }

    // Setup jws Elementor Admin
    elementor.on('frontend:init', jwsElementorAdmin.init.bind(jwsElementorAdmin));
});