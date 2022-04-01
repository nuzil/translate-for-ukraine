
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
            <div class="title">Лікар чи Лікарня</div>
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

<?php include("header.php"); ?>
    <section>
        <article>
            <header>
                <h1 class="mb-0">Де Вам потрібна допомога?</h1>
                <div class="h3 mb-30 fw-l">Оберіть з переліку назву установи.</div>
            </header>
            <div class="page" id="intro">
                <div data-role='accordion'>
                    <div data-role='accordion-item' class="location" id="support_ausl_amt">
                        <div data-role='accordion-trigger' class="location-title">Імміграційна служба та служба громадян</div>
                        <div data-role='accordion-content' class="location-contact">
                            <div class="languages"></div>
                            <div class="channels"></div>
                        </div>
                    </div>
                    <div data-role='accordion-item' class="location" id="support_doctor">
                        <div data-role='accordion-trigger' class="location-title">Лікар чи Лікарня</div>
                        <div data-role='accordion-content' class="location-contact">
                            <div class="languages"></div>
                            <div class="channels"></div>
                        </div>
                    </div>
                    <div data-role='accordion-item' class="location" id="support_education">
                        <div data-role='accordion-trigger' class="location-title">Дитячий садок чи школа</div>
                        <div data-role='accordion-content' class="location-contact">
                            <div class="languages"></div>
                            <div class="channels"></div>
                        </div>
                    </div>
                    <div data-role='accordion-item' class="location" id="support_amt">
                        <div data-role='accordion-trigger' class="location-title">Інші офіційні процедури</div>
                        <div data-role='accordion-content' class="location-contact">
                            <div class="languages"></div>
                            <div class="channels"></div>
                        </div>
                    </div>
                    <div data-role='accordion-item' class="location" id="support_other">
                        <div data-role='accordion-trigger' class="location-title">Інше</div>
                        <div data-role='accordion-content' class="location-contact">
                            <div class="languages"></div>
                            <div class="channels"></div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
    <?php include("footer.php"); ?>

<script>
    var accordion = $("[data-role='accordion']");
    var accordionItem = accordion.children("[data-role='accordion-item']");
    var accordionItemTrigger = accordionItem.children("[data-role='accordion-trigger']");
    
    accordionItemTrigger.click(function(){
        accordion.find("#contact-item").remove();
        accordion.find(".channel-link").removeClass("selected");
        $(this).closest(accordion).find(accordionItem).removeClass("open");
        $(this).parent(accordionItem).addClass( "open" );
    });

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
                        $("#" + key + " .channels").append("<div data-id='" + valueKey + "' class='channel-link'><span>" + value + "</span></div>");
                        channelCounter++;
                    });

                    if (channelCounter == 0) {
                        $("#" + key + " .channels").append("<div>Not Available</div>");
                    }
                });

                $(".channel-link").click(function(){
                    var channel = $(this).attr("data-id");
                    var location = $(this).closest("[id]").attr("id");
                    var contact = $(this).closest(".location-contact");
                    
                    contact.find("#contact-item").remove();
                    contact.find(".channel-link").removeClass("selected");
                    $(this).addClass("selected");

                    $.ajax('getContact.php?channel=' + channel + "&location=" + location,
                        {
                            dataType: 'json',
                            success: function (data, status, xhr) {

                                $("#intro").hide();
                                $("#contact").append(data.name + " - Contact: " +data[channel])

                            }
                        }
                    );
                });
            }
        });
</script>
