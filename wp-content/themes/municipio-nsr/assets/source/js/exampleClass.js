Nsr = Nsr || {};
Nsr.ExampleNamespace = Nsr.Liquid || {};

Nsr.ExampleNamespace.ExampleClass = (function ($) {

	var classVariable = false;

    /**
     * Constructor
     */
	function ExampleClass() {

        this.init();
    }


    /**
     *  mainMenuTabs
     *  Main top menu
     *
     */
    ExampleClass.prototype.init = function () {

        this.exampleFunction({ val1: '1', val2: '2'});
    };


    /**
     *  exampleFunction
     *  test
     *
     *  @param params.val1 string
     *  @param params.val2 string
     */
    ExampleClass.prototype.exampleFunction = function (params) {

        //params.val1
        //params.val2
    };


    return new ExampleClass();

})(jQuery);


