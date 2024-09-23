<fieldset class="fieldset mb-15 edit-address-fieldset">
    <legend>Addresses</legend>
    <div class="fieldset-content-box">
        <table class="table-edit-address table table-bordered">
            <input type="hidden" name="address_csrf_token" id="address_csrf_token" value="<?= generateToken('tableEditAddress') ?>">
            <thead>
                <tr>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th width="15%">State</th>
                    <th width="15%">Country</th>
                    <th>Pincode</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</fieldset>