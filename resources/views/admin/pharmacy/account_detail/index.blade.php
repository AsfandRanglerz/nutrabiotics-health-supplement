<div class="modal fade" id="viewDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Bank Name</label>
                        <div type="text" class="form-control" readonly>
                            {{ isset($detail->name) ? $detail->name : 'No_Record' }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Account Holder Name</label>
                        <div type="text" class="form-control" readonly>
                            {{ isset($detail->accountHolder) ? $detail->accountHolder : 'No_Record' }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Account Number</label>
                        <div type="text" class="form-control" readonly>
                            {{ isset($detail->accountNumber) ? $detail->accountNumber : 'No_Record' }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
