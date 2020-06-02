<!-- Customer Support -->
<div class="modal fade support-modal" id="modal-support" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <!-- From -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <input id="support-from" type="text" :class="{'form-control': true, 'is-invalid': supportForm.errors.has('from')}" 
                                   v-model="supportForm.from" placeholder="Your Email Address">
                            <div class="invalid-feedback">@{{ supportForm.errors.get('from') }}</div>    
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <input id="support-subject" type="text" :class="{'form-control': true, 'is-invalid': supportForm.errors.has('subject')}"
                                   v-model="supportForm.subject" placeholder="Subject">
                            <div class="invalid-feedback">@{{ supportForm.errors.get('subject') }}</div>    
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="7" :class="{'form-control': true, 'is-invalid': supportForm.errors.has('message')}"
                                      v-model="supportForm.message" placeholder="Message"></textarea>
                            <div class="invalid-feedback">@{{ supportForm.errors.get('message') }}</div>    
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Actions -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click.prevent="sendSupportRequest" :disabled="supportForm.busy">
                    <span class="fa fa-btn fa-paper-plane"></span> Send
                </button>
            </div>
        </div>
    </div>
</div>
