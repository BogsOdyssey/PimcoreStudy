pimcore.registerNS("pimcore.plugin.TestBundle");

pimcore.plugin.TestBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("TestBundle ready!");
    }
});

var TestBundlePlugin = new pimcore.plugin.TestBundle();
