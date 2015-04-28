jQuery(document).ready(function() {

    $body = $("body");

    var _token = $('meta[name="csrf-token"]').attr('content');

    jQuery("abbr.timeago").timeago();

    $(function () {$('[data-toggle="tooltip"]').tooltip()})

    //Follow user
	$(document).on( "click", ".follow", function() {
        //console.log('follow');
		var e = $(this);
		var follower_id = e.attr('data-follower');
		//console.log(follower_id);

		$.ajax({
            url: 'add_follower',
            type: 'POST',
            data: { _token:_token, follower_id:follower_id } ,
        })
        .done(function(response) {
        	//console.log(response);
            e.text("Following");
            e.removeClass( "btn-default follow" ).addClass("btn-success unfollow");
            e.prepend("<i class='fa fa-check'></i> ");
        	
	    });

	});

    // Unfollow user
    $(document).on( "click", ".unfollow", function() {

        var e = $(this);
        var follower_id = e.attr('data-follower');
        //console.log(follower_id);

        $.ajax({
            url: 'remove_follower',
            type: 'POST',
            data: { _token:_token, follower_id:follower_id } ,
        })
        .done(function(response) {
            //console.log(response);
            e.text("Follow");
            e.removeClass( "btn-success unfollow" ).addClass("btn-default follow");
            e.prepend("<i class='fa fa-plus'></i> ");
            
        });

    });

    // Infinite scrolling
    $(window).scroll(function(){

        //When we reach the bottom of the page we will call the getData function
        //to execude the ajax
        if($(window).scrollTop() + $(window).height() === $(document).height()){
            getData();
        }
    });

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }    
    });

    //the ajax function which will get the data from the server
    function getData(){


        //var displayedElements = container.children('.data-element').length;
        var displayedElements = $("#count-rows").val();
        //console.log(displayedElements);

        $.ajax({
            type: "POST",
            url: 'homeScrolling',
            data: {
                //here we send the displayed elements
                //to the php function
                _token: _token, displayedData: displayedElements
            }
        }).done(function(response){
            //if everything is ok the server (PHP getData function)
            //will return response with the remaining data
            
            // if the object is empty stop the script
            if(response == "")
            {
                return false;
            }
            var dataElement = '';

            //foreach loop where for every element in the returned array
            //we display li element with the data
            $.each(response, function(key,value){
                //console.log(value);
                if(value.title == null){ title = ""}
                else{ title = value.title }

                if(value.user_id.provider){var avatar = "<img src='" + value.user_id.avatar + "' height='40' width='40' >";}
                else if(value.user_id.provider){var avatar = "<img src='/uploads/avatar/thumbs/" + value.user_id.avatar + "'>";}
                else{var avatar = "<img src='/imgs/avatar.png' height='40' width='40'>";}

                var created_at = convertDate(value.created_at);

                $("#container").append("<div class='main-img-container'><div class='col-md-12 col-md-offset-6 img-container grey-one'>" + avatar
                    + "<p class='fl-left'>" + value.user_id.username + "</p>"
                    + "<abbr class='timeago fl-right' title='" + created_at + "'></abbr></div>"
                    + "<div class='col-md-10 col-md-offset-6 img-container'><img src='uploads/"+ value.file_name +"' class='Image'></div>"
                    + "<div class='col-md-12 col-md-offset-6 img-container post-container'><p><strong>" + title + "</strong></p></div></div><div class='br'></div>"
                );

                dataElement++;
            }); 
            //console.log(dataElement);
            jQuery("abbr.timeago").timeago();


            //last we will append the elements added to the container
            $("#count-rows").val(parseInt(dataElement) + parseInt(displayedElements));
        });
    }

    function convertDate(date)
    {
        var date  = new Date(date);

        // console.log(date);
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        var date_format =  + year + "-" + month + "-" + day;
        return date_format;
    }


})