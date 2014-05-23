var clickedIssue = '';
jQuery(function($){
	var loader = '<div id="tekserve_faq_loading" style="text-align:center; display:none;">\
    	<img src="' + tekserveFaqData[0] + 'images/ajax-loader.gif" />\
    </div>';
	var page = 1;
    var loading = true;
    var postType;
    
    if( tekserveFaqData[1] == "tekserve_faq_device") {
    	postType = "device";
    }
    if( tekserveFaqData[1] == "tekserve_faq_os") {
    	postType = "os";
    }
    var post_obj = tekserveFaqData[2]
    var viewportSize = $( window ).width();
    var slideWidth = viewportSize / 1.5;
	$('.tekserve-faq-'+postType+'-issue').click(function(){
			clickedIssue = this.id;
			$('#tekserve_faq_'+postType+'_content .tekserve-faq-slide-title').text($('#'+clickedIssue).text());
			viewportSize = $( window ).width();
			if ( viewportSize < 501 ) {
				viewportSize = 320;
			}
			$('.tekserve-faq-'+postType+'-issue').removeClass('active');
			$(this).addClass('active');
			$('body #content #tekserve-faq-questions').empty();
			load_posts();
			if(viewportSize < 769) {
				$('#tekserve_faq_'+postType+'_content').css('left', -(viewportSize)+'px' );
				$('#tekserve_faq_'+postType+'_content').attr('faqslide', (viewportSize));
			}
		});
	$('#tekserve_faq_device_content .tekserve-faq-slide-link').click(function() {
		slideWidth = $('#tekserve_faq_device_content').attr('faqslide');
		$('#tekserve_faq_device_content').css('left', 0 );
	});
	$('#tekserve_faq_os_content .tekserve-faq-slide-link').click(function() {
		slideWidth = $('#tekserve_faq_os_content').attr('faqslide');
		$('#tekserve_faq_os_content').css('left', 0 );
	});

    var $content = $('body #content #tekserve-faq-questions');
    var load_posts = function(){
		$.ajax({
			type		: "GET",
			data		: {numPosts : -1, pageNumber: page, issue : clickedIssue, type : postType, citems : post_obj},
			dataType	: "html",
			timeout		: 10000,
			url			: tekserveFaqData[0]+"questionSorter.php",
			beforeSend	: function(){
				$content.append(loader);
				$('#tekserve_faq_loading').fadeIn(250);
			},
			success    : function(data){
				$data = $(data);
				if($data.length){
					$data.fadeOut(250);
					$content.append($data);
					$("#tekserve_faq_loading").fadeOut(250);
					setTimeout($data.fadeIn(500), 500);
					loading = false;
				}
				else {
					$('#tekserve_faq_loading').fadeOut(250);
				}
			},
			error     : function(jqXHR, textStatus, errorThrown) {
				$('#tekserve_faq_loading').fadeOut(250);
				alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
			}
		});
    }
});