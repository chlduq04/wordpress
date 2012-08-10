
// document ready actions
$(document).ready(function() {
	
	// handle tags input fields
	jiffuyAddTagsInput();
	
	// add sortable categories
	$(function() {
		$('ul#userCategories').sortable({
			stop:function(event, ui) {
				jiffuyRebuildCategories();
				jiffuyAddTagsInput();
			},
			disable: true
		});
	});
	
});


/**
 * Function to handle tags input
 */
function jiffuyRebuildCategories() {
	$('ul#userCategories li').each(function(i, item) {
		var element = $(this).html();
			element = element.replace(/-\d/g,"-"+i);
			element = element.replace(/\[\d\]/g,"["+i+"]");
			element = element.replace(/jiffuyRemoveShopCategoryItem\(\d\)/g,"jiffuyRemoveShopCategoryItem("+i+")");
		$(this).html(element);
	});
}


/**
 * Function to handle tags input
 */
function jiffuyAddTagsInput() {
	
	$('div#jiffuy-settings div.tagsinput').remove();
	
	// handle tags input fields
	$('div#jiffuy-settings .tags').each(function(i, item) {
		$(this).tagsInput({
			'defaultText':'Tag hinzufügen'
		});
	});
	
	// handle tags input fields
	$('div#jiffuy-settings .tagsNot').each(function(i, item) {
		$(this).tagsInput({
			'defaultText':'Tag ausschließen'
		});
	});
	
}


/**
 * Function to add a shop main category item
 */
function jiffuyAddShopCategoryItem() {
	
	// counter
	userCategories = userCategories + 1;
	
	// prepare list item
	var element = $('ul#staticCategories li#static-home').clone().html();
		element = element.replace(/\[shopStaticCategories\]/g,"[shopUserCategories]");
		element = element.replace(/-home/g,"-"+userCategories);
		element = element.replace(/\[home\]/g,"["+userCategories+"]");
		element = element.replace(/jiffuyRemoveShopCategoryItem\(home\)/g,"jiffuyRemoveShopCategoryItem("+userCategories+")");
	
	// add new list item
	$('ul#userCategories').append('<li class="ui-state-default">'+element+'</li>');
	
	// clear fields
	$('ul#userCategories li input#shopCategoryName-'+userCategories).attr('value', '');
	$('ul#userCategories li div.categoryDetails input#shopCategorySlug-'+userCategories).attr('value', '').removeAttr('disabled').removeClass('disabled');
	$('ul#userCategories li div.categoryDetails input#shopCategoryTags-'+userCategories).attr('value', '');
	$('ul#userCategories li div.categoryDetails input#shopCategoryTagsNot-'+userCategories).attr('value', '');
	$('ul#userCategories li div.categoryDetails input#shopCategoryTitle-'+userCategories).attr('value', '');
	$('ul#userCategories li div.categoryDetails textarea#shopCategoryDescription-'+userCategories).html('');
	$('ul#userCategories li div.categoryDetails input#shopCategoryMetaDescription-'+userCategories).attr('value', '');
	
	$('ul#userCategories li div#shopCategoryTags-'+userCategories+'_tagsinput > span.tag').remove();
	$('ul#userCategories li div#shopCategoryTagsNot-'+userCategories+'_tagsinput > span.tag').remove();
	
	// add tag handling
	jiffuyAddTagsInput();
	
	// show details
	$('ul#userCategories li div#categoryDetails-'+userCategories).show();
	
}

/**
 * Function to remove a shop main category item
 * @param id
 */
function jiffuyRemoveShopCategoryItem(id) {
	
	// counter
	userCategories = userCategories - 1;
	
	// remove list item
	$('ul#userCategories li div#categoryDetails-'+id).parents('li').remove();
	
	// handle tags input fields
	jiffuyRebuildCategories();
	jiffuyAddTagsInput();
	
}

