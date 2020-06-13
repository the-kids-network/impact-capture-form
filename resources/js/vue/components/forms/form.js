import SparkFormErrors from './errors'

const SparkForm = function (data) {
    var form = this;

    $.extend(this, data);

    /**
     * Create the form error helper instance.
     */
    this.errors = new SparkFormErrors();
    this.busy = false;
    this.statusMessage = undefined

    this.startProcessing = function () {
        form.errors.forget();
        form.statusMessage = undefined
        form.busy = true;
    };

    this.setSuccess = function (message) {
        form.busy = false;
        form.statusMessage = message;
    };

    this.setErrors = function (errors) {
        form.busy = false;
        form.errors.set(errors);
    };

    this.resetFormData = function () {
        // reset form fields since success
        $.extend(this, data);
    }
};

export default SparkForm;