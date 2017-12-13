var updateFormType = function() {
    if (!jQuery('input[name="data[Group][formType]"]').length || 
        jQuery('input[name="data[Group][formType]"]:checked').val()=="simplified") {
        jQuery('#syncCanvasTable').addClass('simplified');
    }
    else {
        jQuery('#syncCanvasTable').removeClass('simplified');
    }
}

jQuery(document).ready(function(){

    var formSubmitted = false;

    // submit to a better url
    jQuery('#syncCanvasForm.prepare').submit(function(e){
        if (!formSubmitted) {
            var submitUrl = jQuery(this).attr('action');
            if (jQuery('#GroupCourse').val()) {
                submitUrl += '/' + jQuery('#GroupCourse').val();
                if (jQuery('#GroupCanvasCourse').val()) {
                    submitUrl += '/' + jQuery('#GroupCanvasCourse').val();
                    if (jQuery('#GroupCanvasGroupCategory:not(:disabled)').length) {
                        if (jQuery('#GroupCanvasGroupCategory').val()) {
                            submitUrl += '/' + jQuery('#GroupCanvasGroupCategory').val();
                        }
                        else {
                            submitUrl += '/new';
                        }
                    }
                }
            }
            formSubmitted = true;
            jQuery(this).attr('action', submitUrl).submit();
            return false;
        }
    });

    // when the canvas course changes, refresh the page to get new canvas course group categories
    jQuery('#syncCanvasForm.prepare #GroupCanvasCourse').change(function(){
        jQuery('#GroupCanvasGroupCategory').attr('disabled','disabled'); 
        jQuery('#syncCanvasForm').submit();
        jQuery('#syncCanvasForm select').attr('disabled','disabled');
    });

    if (jQuery('#syncCanvasForm.sync-screen').length) {

        jQuery('#syncCanvasForm input[type="checkbox"]').prop('checked',false);
    
        var numMembersToShow = jQuery('#syncCanvasTable').data('nummembers');
    
        // Select All / Select None
    
        jQuery('#syncCanvasForm #canvasHeading .selectAll').click(function(){
            jQuery('table.canvasGroup input[type="checkbox"]:not(:checked)').click();
            return false;
        });
        jQuery('#syncCanvasForm #canvasHeading .selectNone').click(function(){
            jQuery('table.canvasGroup input[type="checkbox"]:checked').click();
            return false;
        });
        jQuery('#syncCanvasForm #iPeerHeading .selectAll').click(function(){
            jQuery('table.iPeerGroup input[type="checkbox"]:not(:checked)').click();
            return false;
        });
        jQuery('#syncCanvasForm #iPeerHeading .selectNone').click(function(){
            jQuery('table.iPeerGroup input[type="checkbox"]:checked').click();
            return false;
        });
        
        // Expand / Collapse All
    
        jQuery('#syncCanvasForm #iPeerHeading .expandAll').click(function(){
            jQuery('table.iPeerGroup tbody tr').slideDown();
            jQuery('table.iPeerGroup tbody').slideDown();
            jQuery('table.iPeerGroup .showLessMembers, table.iPeerGroup .showMinMembers').show();
            jQuery('table.iPeerGroup .showMoreMembers, table.iPeerGroup .showAllMembers').hide();
            jQuery('table.iPeerGroup th').addClass('expanded-after').removeClass('collapsed-after');
            return false;
        });
        jQuery('#syncCanvasForm #canvasHeading .expandAll').click(function(){
            jQuery('table.canvasGroup tbody tr').slideDown();
            jQuery('table.canvasGroup tbody').slideDown();
            jQuery('table.canvasGroup .showLessMembers, table.canvasGroup .showMinMembers').show();
            jQuery('table.canvasGroup .showMoreMembers, table.canvasGroup .showAllMembers').hide();
            jQuery('table.canvasGroup th').addClass('expanded-after').removeClass('collapsed-after');
            return false;
        });
        jQuery('#syncCanvasForm #iPeerHeading .collapseAll').click(function(){
            jQuery('table.iPeerGroup tbody').find('tr:not(".showMoreLessMembers"):gt(' + (numMembersToShow - 1) + ')').slideUp();
            jQuery('table.iPeerGroup tbody').slideUp();
            jQuery('table.iPeerGroup .showLessMembers, table.iPeerGroup .showMinMembers').hide();
            jQuery('table.iPeerGroup .showMoreMembers, table.iPeerGroup .showAllMembers').show();
            jQuery('table.iPeerGroup th').removeClass('expanded-after').addClass('collapsed-after');
            return false;
        });
        jQuery('#syncCanvasForm #canvasHeading .collapseAll').click(function(){
            jQuery('table.canvasGroup tbody').find('tr:not(".showMoreLessMembers"):gt(' + (numMembersToShow - 1) + ')').slideUp();
            jQuery('table.canvasGroup tbody').slideUp();
            jQuery('table.canvasGroup .showLessMembers, table.canvasGroup .showMinMembers').hide();
            jQuery('table.canvasGroup .showMoreMembers, table.canvasGroup .showAllMembers').show();
            jQuery('table.canvasGroup th').removeClass('expanded-after').addClass('collapsed-after');
            return false;
        });
        
        // Click group name (table header) to show / hide members (tbody)
    
        jQuery('.standardtable th').click(function(e){
            if(e.target == this){
                jQuery(this).closest('table').find('tbody').toggle();
                if (jQuery(this).hasClass('expanded-after')) {
                    jQuery(this).removeClass('expanded-after').addClass('collapsed-after');
                }
                else if (jQuery(this).hasClass('collapsed-after')) {
                    jQuery(this).removeClass('collapsed-after').addClass('expanded-after');
                }
                return false;
            }
        });
    
        // When (un)checking a group, (un)check all its eligible members
    
        jQuery('#syncCanvasTable table th input[type="checkbox"]').change(function(){
            console.log('tttt');
            if (jQuery(this).is(':checked')) {
                jQuery(this).parents('table.standardtable').find('tr td span:not(.disabled)').addClass('check-before');
            }
            else {
                jQuery(this).parents('table.standardtable').find('tr td span:not(.disabled)').removeClass('check-before');
            }
        });
    
        // Show all / more / less / min members
    
        jQuery('#syncCanvasTable .standardtable').each(function(){
            if (jQuery(this).find('tbody tr:not(".showMoreLessMembers")').length > numMembersToShow) {
                jQuery(this).find('tbody tr:not(".showMoreLessMembers"):gt(' + (numMembersToShow - 1) + ')').hide();
            }
        });
        
        jQuery('.showAllMembers').click(function(){
            var members = jQuery(this).closest('tr').siblings();
            members.show();
            jQuery(this).siblings('.showLessMembers, .showMinMembers').show();
            jQuery(this).hide();
            jQuery(this).siblings('.showMoreMembers').hide();
            return false;
        });
    
        jQuery('.showMoreMembers').click(function(){
            var members = jQuery(this).closest('tr').siblings();
            var visibleMembers = members.filter(':visible');
            members.filter(':lt(' + (visibleMembers.length + numMembersToShow) + ')').show();
            jQuery(this).siblings('.showLessMembers, .showMinMembers').show();
            if (members.filter(':visible').length == members.length) {
                jQuery(this).hide();
                jQuery(this).siblings('.showAllMembers').hide();
            }
            return false;
        });
    
        jQuery('.showLessMembers').click(function(){
            var members = jQuery(this).closest('tr').siblings();
            var visibleMembers = members.filter(':visible');
            if ((visibleMembers.length - numMembersToShow) < numMembersToShow) {
                members.filter(':gt(' + (numMembersToShow - 1) + ')').hide();
            }
            else {
                members.filter(':gt(' + (visibleMembers.length - numMembersToShow - 1) + ')').hide();
            }
            jQuery(this).siblings('.showMoreMembers, .showAllMembers').show();
            if (members.filter(':visible').length == numMembersToShow) {
                jQuery(this).hide();
                jQuery(this).siblings('.showMinMembers').hide();
            }
            return false;
        });
        
        jQuery('.showMinMembers').click(function(){
            var members = jQuery(this).closest('tr').siblings();
            members.filter(':gt(' + (numMembersToShow - 1) + ')').hide();
            jQuery(this).siblings('.showMoreMembers, .showAllMembers').show();
            jQuery(this).siblings('.showLessMembers').hide();
            jQuery(this).hide();
            return false;
        });
    
        jQuery('#syncCanvasForm .collapseAll.collapseOnLoad').click();
    
        jQuery('input[name="data[Group][formType]"]').change(updateFormType);
        updateFormType();   
    } 
});