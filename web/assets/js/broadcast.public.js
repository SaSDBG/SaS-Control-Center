$(document).ready(function() {
    // getBroadcasts();
    var timer = $.timer(function() {
        getBroadcasts();
    });
    timer.set({time: 30000, autostart: true});
});

var options = {
    controls: false,
    auto: true,
    pause: 7000,
    useCss: false,
    
}

var newsHandler = new News(".newsitem", "#placeholder");
var newsParser = new NewsParser();
var news1 = new NewsItem("#news1", ".newsitem", options);
var news2 = new NewsItem("#news2", ".newsitem", options);
var news3 = new NewsItem("#news3", ".newsitem", options);

function getBroadcasts()
{
    $.get(sourceUrl, function(data) {
        parseBroadcastData(data);
    });
}

function parseBroadcastData(data)
{
    // Data available
    if (data.length > 0)
    {
        newsHandler.disablePlaceholder();

        var urgentItems = newsParser.parseUrgentItems(data);
        var standardItems = newsParser.parseStandardItems(data);
        var adItems = newsParser.parseAdItems(data);

        // Urgent items available
        if (urgentItems.length > 0)
        {
            console.log(urgentItems);
            news1.addItems(urgentItems);
        }
        else
        {
            news1.destroySlider();
            news1.hide();
        }

        // Standard items available
        if (standardItems.length > 0)
        {
            news2.addItems(standardItems);
        }
        else
        {
            news2.destroySlider();
            news2.hide();
        }

        // Ad items available
        if (adItems.length > 0)
        {
            news3.addItems(adItems);
        }
        else
        {
            news3.destroySlider();
            news3.hide();
        }
    }
    else
    {
        news1.destroySlider();
        news2.destroySlider();
        news3.destroySlider();
        news1.hide();
        news2.hide();
        news3.hide();
        newsHandler.enablePlaceholder();
    }
}

function News(itemClass, placeHolderClass)
{
    this.newsItemClass = itemClass;
    this.placeHolderClass = placeHolderClass;

    this.fixItems = function() {
        $(this.newsItemClass)
    };

    this.disablePlaceholder = function() {
        $(this.placeHolderClass).addClass("hidden");
    };

    this.enablePlaceholder = function() {
        $(this.placeHolderClass).removeClass("hidden");
    };
}

function NewsParser()
{
    this.urgentIds = [1, 2];
    this.standardIds = [0];
    this.adIds = [3];

    this.parseUrgentItems = function(data) {
        var items = [];
        var ids = this.urgentIds;

        $.each(data, function(key, value) {
            if ($.inArray(value.type, ids) >= 0)
                items.push(value);
        });

        return items;
    };

    this.parseStandardItems = function(data) {
        var items = [];
        var ids = this.standardIds;
        $.each(data, function(key, value) {
            if ($.inArray(value.type, ids) >= 0)
                items.push(value);
        });

        return items;
    };

    this.parseAdItems = function(data) {
        var items = [];
        var ids = this.adIds;
        $.each(data, function(key, value) {
            if ($.inArray(value.type, ids) >= 0)
                items.push(value);
        });

        return items;
    };

}

function NewsItem(selector, newsItemSelector, sliderOptions)
{
    this.selector = selector;
    this.newsItemSelector = newsItemSelector;
    this.sliderOptions = sliderOptions;
    this.savedIds = [];
    this.setUp = false;
    this.shown = false;
    
    this.sliderObj = null;

    this.show = function() {
        $(this.selector).removeClass("hidden");
        this.shown = true;
    };

    this.hide = function() {
        $(this.selector).addClass("hidden");
        this.shown = false;
    };

    this.fixItems = function() {
        var selector = this.selector + " * " + this.newsItemSelector;
       // console.log(selector);
        $(selector).css('height', ($(window).height() - 370) / 3);
    };

    this.setupSlider = function() {
        if (this.setUp)
            this.destroySlider();

        this.sliderObj = $(this.selector).bxSlider(this.sliderOptions);
        this.setUp = true;
    };
    
    this.reloadSlider = function() {
        if(!this.setUp)
            this.setupSlider();
        this.sliderObj.reloadSlider();
    }

    this.destroySlider = function() {
        if (this.setUp)
            this.sliderObj.destroySlider();
        this.setUp = false;
    };

    this.addItems = function(items) {
        var target = this.selector;
        var savedIds = this.savedIds;
        var ids = [];

        if (!this.shown)
            this.show();

        // Items which are not added yet
        $.each(items, function(key, value) {
            if ($.inArray(value.id, savedIds) === -1)
            {

                $(target).append(value.html);
            }
            ids.push(value.id);
        });

        // Delete ids which are not active anymore
        $.each(this.savedIds, function(key, value) {
            // Not needed anymore
            if ($.inArray(value, ids) === -1)
            {
               // console.log("Delete " + value);
                var sel = "#nws" + value;
                
                while($(sel).length > 0)
                    $(sel).remove();
            }
        });
        this.savedIds = ids;
        this.fixItems();

        if (!this.setUp)
            this.setupSlider();
        else
            this.reloadSlider();
            
    };
}
