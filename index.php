<html>
<head>
    <script src="jquery-3.5.1.min.js"></script>
    <style>
        .location {
            display: none;
        }
        .location .title {
            font-size: 22px;
        }
    </style>
</head>
<body>
<div id="intro">
    <h2>Де Вам потрібна допомога</h2>
    <div>
        <div class="location" id="support_ausl_amt">
            <div class="title">Імміграційна служба та служба громадян </div>
            <span class="languages"></span>
            <span class="channels"></span>
        </div>
        <div class="location"  id="support_doctor">
            <div class="title">Врач чи Лікарня</div>
            <span class="languages"></span>
            <span class="channels"></span>
        </div>
        <div class="location" id="support_education">
            <div class="title">Дитячий садок чи школа</div>
            <span class="languages"></span>
            <span class="channels"></span>
        </div>
        <div class="location" id="support_amt">
            <div class="title">інші офіційні процедури</div>
            <span class="languages"></span>
            <span class="channels"></span>
        </div>
        <div class="location" id="support_other">
            <div class="title">Інше</div>
            <span class="languages"></span>
            <span class="channels"></span>
        </div>
    </div>
</div>
<div id="contact">
</div>
<script>
    $.ajax('getAvailableChannels.php',   // request url
        {
            dataType: 'json',
            success: function (data, status, xhr) { // success callback function
                $.each( data, function( key, value ) {
                    $("#" + key).show();
                    // $.each( value.languages, function( valueKey, value ) {
                    //     $("#" + key + " .languages").append("<div>" + value + "</div>");
                    // });
                    var channelCounter = 0;
                    $.each( value.channels, function( valueKey, value ) {
                        $("#" + key + " .channels").append("<div data-id='" + valueKey + "' class='channel-link'>" + value + "</div>");
                        channelCounter++;
                    });
                    if (channelCounter == 0) {
                        $("#" + key + " .channels").append("<div>Not Available</div>");
                    }
                });
                $(".channel-link").click(function(){
                    var channel = $(this).attr("data-id");
                    var location = $(this).parent().parent().attr("id");
                    $.ajax('getContact.php?channel=' + channel + "&location=" + location,
                        {
                            dataType: 'json',
                            success: function (data, status, xhr) {
                                $("#intro").hide();
                                $("#contact").append(data.name + " - Contact: " +data[channel])
                            }
                        });
                });
            }
        });
</script>
</body>
</html>
