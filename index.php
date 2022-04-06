<?php include("header.php"); ?>
    <section>
        <article>
            <header>
                <h1 class="mb-10">Контакти перекладачів</h1>
                <div class="h3 mb-10 fw-l">Оберіть з переліку назву установи та спосіб зв‘язку</div>
                <div class="h5 mb-30 fw-l">Зв'яжіться з перекладачем за декілька хвилин, щоб перевірити чи він доступний і коротко пояснити йому суть наступного завдання</div>
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
    <section>
        <article class="mt-40 mb-30 py-40 px-60 bgc-g96">
            <header>
                <h2 class="h3 mt-0 mb-10">Бажаєте допомогти?</h2>
            </header>
            <div class="h4 mb-20 fw-l">Пройдіть реєстрацію та вкажіть як найкраще з вами зв&lsquo;язатися.</div>
            <a class="btn" href="https://docs.google.com/forms/u/2/d/e/1FAIpQLScXAiC6dAEWw4oTCIUV6eXaHT0SjXUgAJGnw-_7jdhSSxLaDw/formResponse?embedded=true" target="_blank">Registrieren</a>
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
                                if (data.length == 0) {
                                    contact.append("<div id='contact-item'>Not Available</div>");
                                }else{
                                    contact.append("<div id='contact-item'>" + data.name + " - Contact: " +data[channel] + "</div>");
                                }
                            }
                        }
                    );
                });
            }
        });
</script>
