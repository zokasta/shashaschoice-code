(function($) {

	$(document).ready(function() {

		$(".goldsmith-product360-btn").appendTo(".flex-viewport");

		$('.goldsmith-product360-btn a').on('click', function(e) {
			e.preventDefault();
			init($('.goldsmith-360-view.goldsmith-product-360'));
		});

		$('.goldsmith-product360-btn a').magnificPopup({
			type: 'inline',
            fixedBgPos: true,
            fixedContentPos: true,
            closeBtnInside: true,
            removalDelay: 0,
            mainClass: 'goldsmith-mfp-slide-bottom',
            tClose: '',
            tLoading: '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
            closeMarkup: '<div title="%title%" class="mfp-close goldsmith-mfp-close"></div>',
		});


		function init($this) {
			var data = $this.data('args');

			if (!data || $this.hasClass('goldsmith-360-view-inited')) {
				return false;
			}

			$this.ThreeSixty({
				totalFrames : data.frames_count,
				endFrame    : data.frames_count,
				currentFrame: 1,
				imgList     : '.goldsmith-360-view-images',
				progress    : '.spinner',
				imgArray    : data.images,
				height      : data.height,
				width       : data.width,
				responsive  : true,
				navigation  : true,

			});

			$this.addClass('goldsmith-360-view-inited');
		}
	});

})(jQuery);
