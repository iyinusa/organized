<?php include(APPPATH.'libraries/inc.php'); ?>
<div id="wrapper">
    <div class="normalheader transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <a class="small-header-action" href="javascript:;">
                    <div class="clip-header">
                        <i class="fa fa-arrow-up"></i>
                    </div>
                </a>
                <h4 class="font-light m-b-xs text-success">
                    <strong><i class="pe-7s-cash fa-2x"></i> CHARTS OF ACCOUNTS</strong> <a href="<?php echo base_url('account/chart'); ?>" class="btn btn-primary btn-xs"><i class="fa fa-book"></i> ALL</a> <a  class="btn btn-primary btn-xs text-right" data-toggle="modal" data-target="#addAccount"><i class="fa fa-book"></i> ADD ACCOUNT</a>
                    
                    <div id="addAccount" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="hpanel">
                            <div class="panel-footer" style="color:#3F5872;">
                                <div class="panel-tools">
                                    <a class="close" data-dismiss="modal"><i class="pe pe-7s-close" style="color:#3F5872;"></i></a>
                                </div>
                                <strong>NEW ACCOUNT</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>Quickly create or edit your account by filling the following fields</p>
                                        <form>
                                            <div class="form-group col-lg-12">
                                                <label class="col-lg-4 text-right">Account Type<span class="impor">*</span></label>
                                                <div class="col-lg-8">
                                                    <select class="form-control input-xs" placeholder="10000(Bank)">
                                                        <option>10000 (Bank)</option>
                                                        <option>11000 (Account Receiveable)</option>
                                                        <option>12000 (Inventory)</option>
                                                        <option>13000 (Other Current Assets)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="col-lg-4 text-right">Account Name<span class="impor">*</span></label>
                                                <div class="col-lg-8">
                                                    <input class="form-control input-xs" type="text" placeholder="New Account">
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="col-lg-4 text-right">This Account is a sub-account of</label>
                                                <div class="col-lg-8">
                                                    <select class="form-control input-xs" placeholder="No Parent Account">
                                                        <option>No Parent Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <p>Use Families to classify your Accounts and to calculate Totals. Warning Families are not accessible during data entry.</p>
                                            <div class="form-group col-lg-12">
                                                <label class="col-lg-4 text-right">Is this Account a Parent<span class="impor">*</span></label>
                                                <div class="col-lg-8">
                                                    <select class="form-control input-xs" placeholder="No">
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <p>Account numbers are proposed automatically to the Account Type and its sub-account classification. You can enter the account number manually.</p>
                                            <div class="form-group col-lg-12">
                                                <label class="col-lg-4 text-right">Account Number<span class="impor">*</span></label>
                                                <div class="col-lg-8">
                                                    <input class="form-control input-xs" type="text" placeholder="14001">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                          <div class="panel-footer">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </h4>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="hpanel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <input class="form-control input-sm m-b-md" id="filter" placeholder="Search for an account" type="text">
                        <table id="" class="table table-condensed table-hover display" data-page-size="8" data-filter="#filter">
                            <thead>
                                <tr>
                                    <th class="">ACCOUNT</th>
                                    <th class="text-right">BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="main">
                                    <td>10000 Bank</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">10100 Company Checking Account</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">10014 Petty Cash</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">10024 Payroll Checking</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">10400 Savings</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">10900 Bank in Transit</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                
                                <tr class="main">
                                    <td>11000 Account Receivable</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">11100 Accounts Receivable: Miscelleneous Customers</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">11400 Other Receivables</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                                <tr class="sub">
                                    <td class="first">11200 Allowance for Doubtfil Account</td>
                                    <td class="text-right">&#8358;0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>