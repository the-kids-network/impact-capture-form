const Http = {
    /**
     * Helper method for making POST HTTP requests.
     */
    post(uri, form) {
        return this.sendForm('post', uri, form);
    },


    /**
     * Helper method for making PUT HTTP requests.
     */
    put(uri, form) {
        return this.sendForm('put', uri, form);
    },


    /**
     * Helper method for making PATCH HTTP requests.
     */
    patch(uri, form) {
        return this.sendForm('patch', uri, form);
    },


    /**
     * Helper method for making DELETE HTTP requests.
     */
    delete(uri, form) {
        return this.sendForm('delete', uri, form);
    },


    /**
     * Send the form to the back-end server.
     *
     * This function will clear old errors, update "busy" status, etc.
     */
    sendForm(method, uri, form) {
        return new Promise((resolve, reject) => {
            form.startProcessing();

            axios[method](uri, JSON.parse(JSON.stringify(form)))
                .then(response => {
                    form.setSuccess(response.data.status);
                    resolve(response.data);
                })
                .catch(errors => {
                    form.setErrors(errors.response.data.errors);
                    reject(errors.response.data);
                });
        });
    }
};

export default Http
