<?php include("header.php"); ?>
    <section>
        <article>
            <header>
            <h1 class="mb-0">Де Вам потрібна допомога?</h1>
            <div class="h3 mb-30 fw-l">
                    <p><em>Виберіть відповідний розділ та спосіб зв&lsquo;язку, програма вибере для вас доступного на даний момент перекладача з яким ви зможете зв&lsquo;язатися.</em></p>
                    <p><em>Зв'яжіться з перекладачем за декілька хвилин, щоб перевірити чи він доступний і коротко пояснити йому суть наступного завдання.</em></p>
                    <p><em>&nbsp;</em></p>
                    <p><em><strong>Ви бажаєте допомогти?</strong></em></p>
                    <p><em>Пройдіть реєстрацію та вкажіть як людям, потребуючим допомогу, найкраще з вами зв&lsquo;язатися</em></p></div>
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
                                contact.append("<div id='contact-item'>" + data.name + " - Contact: " +data[channel] + "</div>");

                            }
                        }
                    );
                });
            }
        });
</script>
