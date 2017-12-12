var dimension;
lightboxLoaded = Array();

$(function()
{
    dimension = DetectDimension();

    // Drive the .link_custom_lightbox links
    $('body').on('click', 'a.link_custom_lightbox', function(event)
    {
        event.preventDefault();

        // Check if a lightbox with this parameters has already been created and store it if it's the case
        var idLB = CustomLightbox.prototype.getDataTag($(this).attr('data-content'), $(this).attr('data-params'));
        var objLightbox = findObjByID(lightboxLoaded, idLB);
        if(!objLightbox) // if not, instance a new lightbox
            objLightbox = new CustomLightbox($(this));
        else // if yes, just open the lightbox concerned
            objLightbox.openLightbox();
    });

    // Call the general function for resizing lightboxes
    $(window).resize(function()
    {
        CustomLightbox.prototype.resizeLightbox();
    });
});


/**
 * Constructor of the class CustomLightbox
 * @param link jQuery Object or String
 * @param openAuto Boolean default = true
 * @param noClose Boolean default = false
 * @param reloadable Boolean default = false
 *
 * If link is a correct jQuery Object (typically the common link used), there is no need to complete the others parameters. The constructor will take all informations from
 * the data-parameters in the html (data-content, data-params, data-method, data-reload, data-noclose).
 * link can be a simple HTML string. In this case, default configuration will be chosen, and others params should be completed only if you want to personalize your lightbox
 */
function CustomLightbox(link, openAuto = true, noClose = false, reloadable = false){
    var objLightbox = this;
    // check if link is a correct jQuery Object qith at least the only required parameters: data-content
    if(link instanceof jQuery && link.attr('data-content') && link.attr('data-content') != '' && link.attr('data-content') !== null)
    {
        objLightbox.ID = CustomLightbox.prototype.getDataTag(link.attr('data-content'), link.attr('data-params'));
        objLightbox.dataContent = link.attr('data-content').split('-');
        objLightbox.dataMethod = link.attr('data-method') || 'POST';
        objLightbox.dataReload = (link.attr('data-reload') == 'true') || reloadable;
        objLightbox.noClose = (link.attr('data-noclose') == 'true') || noClose;
        objLightbox.destroy = (link.attr('data-destroy') == 'true') || false;

        if(link.attr('data-params') && link.attr('data-params') != '' && link.attr('data-params') !== null)
            objLightbox.dataParams = link.attr('data-params').split(',');
        else
            objLightbox.dataParams = null;
    }
    else if(typeof(link) == 'string')  // if link is a custom string, set differents properties to the CustomLightbox Object
    {
        objLightbox.ID = 'custom-content-';
        objLightbox.ID += uniqid();
        objLightbox.noClose = noClose;
        objLightbox.dataReload = reloadable;
        objLightbox.customContent = link;
    }
    else
    {
        console.log('Content not defined.')  // You sent some shitty things to my beloved constructor. You're dead to me.
    }

    var promise = objLightbox.getLightboxContainer(); // request the html template for the lightbox
    // we need to delay the next instructions until we get the template, so we use the properties of a Promise Object.
    // I didn't realy get what is this thing but hey ! that works :3
    promise.then(function(data){
        // parse the template into a jQuery Object, store it in our object properties then append it to the DOM
        objLightbox.container = $($.parseHTML(data)).attr('id', objLightbox.ID);
        $('body').append(objLightbox.container);
        lightboxLoaded.push(objLightbox); // Store our object in a global array of lightbox loaded. Very important part. Don't miss it.
        if(openAuto)
            objLightbox.openLightbox();
        if(objLightbox.customContent)
            objLightbox.getContent(objLightbox.customContent);
        else
            objLightbox.getContent();
    });
}

/**
 * Ajax request that return the lightbox html template
 * Side note: We keep this because we can access and edit the template easily, but it force us to delay some instructions (see Constructor) and it is not very elegant.
 * Should we screw this and write our template in this file in order to go faster ?
 */
CustomLightbox.prototype.getLightboxContainer = function()
{
    return $.ajax({
        url : '/system/modules/wem-custom-lightbox/assets/cc_lightbox.html',
    });
}

/**
 * Get the content with an ajax request then send it to setContent
 * @param customContent String or Boolean
 */
CustomLightbox.prototype.getContent = function(customContent = false){
    var objLightbox = getObjLightbox(this);

    objLightbox.resizeLightbox();

    if(!customContent)
    {
        $.ajax(
        {
            type: "POST",
            data:
            {
                TL_LIGHTBOX: true,
                REQUEST_TOKEN: rt,
                value : objLightbox.dataContent,
                method : objLightbox.dataMethod,
                params : objLightbox.dataParams
            }
        })
        .done(function(msg)
        {
            objLightbox.setContent(msg);
        })
        .fail(function( msg )
        {
            objLightbox.setContent("<div>Erreur: "+msg+"</div>");
        });
    }
    else  // if we have custom content to display, no need to ajaxify it
    {
        objLightbox.setContent(customContent);
    }
}

/**
 * Function that set up all content to the right place and do some processes according to parameters
 * @param content String
 */
CustomLightbox.prototype.setContent = function(content){
    var objLightbox = getObjLightbox(this);
    var lbLoader = objLightbox.container.find(".loader");
    var lbContent = objLightbox.container.find('.content');
    lbLoader.hide();
    lbContent.append( $($.parseHTML(content,true)) );
    // if we have the reload option enabled, append the button to the template and set the event
    if(objLightbox.dataReload)
    {
        var reloadButton = $($.parseHTML("<div class='reload_lightbox fa fa-refresh'></div>"));
        reloadButton.unbind('click').bind('click',function(event)
        {
            event.preventDefault();
            reloadButton.unbind('click').remove();
            objLightbox.refreshLightbox();
        });
        objLightbox.container.find('.close_lightbox').before(reloadButton);
    }
    // If we have a form in the loaded lightbox, we will add three hidden fields so that the form is happy and works with Francis.
    lbContent.children('.formbody').prepend('<input type="hidden" name="value[0]" value="'+content[0]+'" />');
    lbContent.children('.formbody').prepend('<input type="hidden" name="value[1]" value="'+content[1]+'" />');
    lbContent.children('.formbody').prepend('<input type="hidden" name="TL_LIGHTBOX" value="1" />');
    // remember to abuse this function, cuz resizing is life.
    objLightbox.resizeLightbox();
    try{
        objApp.loadPageElements();
    }
    catch(e){

    }
}

/**
 * Open the Lightbox. Wow.
 */
CustomLightbox.prototype.openLightbox = function(){
    var objLightbox = getObjLightbox(this);

    $('html').addClass('no-overflow');
    objLightbox.container.removeClass('hidden').addClass('active');
    objLightbox.container.bind('click', function(event)
    {
        if($(event.target).hasClass('custom_lightbox') || $(event.target).hasClass('close_lightbox'))
        {
            event.preventDefault();
            // if you don't have my Toastr's functions, you're screwed. Nah, i'm joking. But you'd better have it.
            if(objLightbox.noClose && typeof toastrNoRest === "function")
                toastrNoRest('warning', 'Traitement en cours - cette fenêtre ne peut être fermée.');
            else
                objLightbox.closeLightbox();
        }
    });
}

/**
 * Close the Lightbox. Wow again.
 */
CustomLightbox.prototype.closeLightbox = function(){
    var objLightbox = getObjLightbox(this);
    objLightbox.container.addClass('hidden').removeClass('active');

    // If there is no lightboxes activated, remove the class for HTML Dom
    if($('.custom_lightbox.active').length == 0)
        $('html').removeClass('no-overflow');

    if(objLightbox.destroy)
        objLightbox.destroyLightbox();
}

/**
 * Refresh the Lightbox. Wow again².
 */
CustomLightbox.prototype.refreshLightbox = function(){
    var objLightbox = getObjLightbox(this);
    objLightbox.container.find('.content').html('');
    objLightbox.container.find('.loader').show();
    if(objLightbox.customContent)
        objLightbox.getContent(objLightbox.customContent);
    else
        objLightbox.getContent();
}

/**
 * Destroy the Lightbox. Wow again.
 */
CustomLightbox.prototype.destroyLightbox = function(){
    var objLightbox = getObjLightbox(this);
    objLightbox.container.addClass('hidden').removeClass('active');

    // If there is no lightboxes activated, remove the class for HTML Dom
    if($('.custom_lightbox.active').length == 0)
        $('html').removeClass('no-overflow');

    var idLb = $.inArray(objLightbox,lightboxLoaded)
    objLightbox.container.remove();
    lightboxLoaded.splice(idLb,1);
}

/**
 * Function that resize the Lightbox. Official whore of this plugin.
 */
CustomLightbox.prototype.resizeLightbox = function(){
    var objLightbox = getObjLightbox(this);
    dimension = DetectDimension();
    var marginTopContent = (dimension[1] / 2) - (objLightbox.container.find('.container').innerHeight() / 2);
    if(marginTopContent < 50)
        marginTopContent = 50;
    objLightbox.container.find('.container').css({'margin' : marginTopContent + "px auto"});
}

/**
 * Short utility function that return the ID af any lightbox according to his parameters. Only the parameters are sent because sometimes we need to get an ID without
 * having the full object
 * @param dataContent String
 * @param dataParams String
 */
CustomLightbox.prototype.getDataTag = function(dataContent, dataParams = '')
{
    // Format the data tag
    var dataTag = dataContent;
    // Add the params if they are defined
    if(dataParams != '' && dataParams !== null)
        dataTag = dataTag + "-" + btoa(dataParams.replace(/=/g, ''));
    // Return it as a string
    return dataTag;
}

/**
 * Short utility function that check if the Object sent is conform and return it. If it's not conform, return a fake object with the current lightbox showed
 * @param obj CustomLightbox Object or undefined
 */
function getObjLightbox(obj)
{
    if(obj.hasOwnProperty('ID'))
        return obj;
    else
    {
        return { 'container' : $('.custom_lightbox.active').first() };
    }
}

/**
 * Short utility function that return the CustomLightbox Object matching the correct ID
 * @param arrObj Array of CustomLighbtox
 * @param ID String
 */
function findObjByID(arrObj, ID)
{
    var result = false;
    $.each(arrObj, function(index, obj){
        if(obj.ID == ID)
        {
            result = obj;
            return false;
        }
    });
    return result;
}

/**
 * Short utility function that return the CustomLightbox Object matching the correct options.
 * @param arrObj Array of CustomLighbtox
 * @param arrOptions Object
 *
 * arrOptions need to be an object like this: {nameParameter : valueParemeter,...}
 * works as intended width one parameter, but need more investigation to know if it works with plenty
 */
function findObjBy(arrObj, arrOptions)
{
    var result = false;
    $.each(arrObj, function(index, obj){
        $.each(arrOptions, function(index, value){
            if(obj[index] == value)
            {
                result = obj;
                return false;
            }
        });
    });
    return result;
}

/**
 * Common function ussed to know the browser dimensions
 */
function DetectDimension()
{
    dim_win_width = window.innerWidth;
    dim_win_height = window.innerHeight;
    dim_win = [dim_win_width,dim_win_height];
    return dim_win;
}