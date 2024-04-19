$(".show-password").click(function () {
    let icon = $(this).children().attr('name');
    if(icon === 'eye'){
        $(this).children().attr('name','eye-off')
        $(this).parent(".input-wrapper").find(".form-control").attr('type', 'text');
    }else{
        $(this).children().attr('name','eye');
        $(this).parent(".input-wrapper").find(".form-control").attr('type', 'password');
    }
});

$('.carousel-offering').owlCarousel({
    stagePadding: 15,
    loop: false,
    margin: 16,
    nav: false,
    dots: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 2,
        }
    }
});
$('.carousel-no-padding').owlCarousel({
	loop: false,
	margin: 0,
	nav: false,
	dots: false,
	responsiveClass: true,
	responsive: {
		0: {
			items: 1,
		},
		768: {
			items: 2,
		}
	}
});
$('.carousel-one').owlCarousel({
	stagePadding: 15,
	loop: true,
	margin: 16,
	nav: false,
	dots: false,
	responsiveClass: true,
	items: 1,
});
$('.carousel-document-audio').owlCarousel({
	stagePadding: 15,
	loop: true,
	margin: 16,
	nav: false,
	dots: false,
	responsiveClass: true,
	responsive: {
		0: {
			items: 3,
		},
		768: {
			items: 5,
		}
	}
});
$('.carousel-video').owlCarousel({
	stagePadding: 15,
	loop: true,
	margin: 16,
	nav: false,
	dots: false,
	responsiveClass: true,
	responsive: {
		0: {
			items: 2,
		},
		768: {
			items: 3,
		},
		1080: {
			items: 4,
		}
	}
});

$('#appCapsule').addClass('bg-white full-height');