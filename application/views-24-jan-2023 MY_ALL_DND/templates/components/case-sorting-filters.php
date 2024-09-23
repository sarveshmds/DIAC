<div class="nav-tabs-custom border-0 sorting-navtab">
    <!-- <ul class="nav nav-tabs bg-white">
        <li class="active"><a href="#sorting_tab_1" data-toggle="tab" aria-expanded="true"><i class='fa fa-sort-alpha-asc'></i> Case Sorting</a></li>
    </ul> -->
    <div class="tab-content">
        <div class="tab-pane active" id="sorting_tab_1">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-md-3 col-sm-4 col-xs-12 required">
                    <select name="sorting_by" id="sorting_by" class="form-control">
                        <option value="">Select Sorting By</option>
                        <option value="case_no">Case No.</option>
                        <option value="case_title">Case Title</option>
                        <option value="recieved_on">Ref. Received On</option>
                        <option value="registered_on">Date of Registration</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12 required">
                    <select name="sort_to" id="sort_to" class="form-control">
                        <option value="">Select Sort To</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">

                    <button type="submit" class="btn bg-navy" id="btn_rg_case_sorting"><i class='fa fa-sort-alpha-asc'></i> Sort</button>
                    <button type="submit" class="btn btn-custom-2" id="btn_rg_case_reset_sorting"><i class='fa fa-refresh'></i> Reset Sort</button>
                </div>
            </div>
        </div>

    </div>

</div>