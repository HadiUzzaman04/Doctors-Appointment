<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <x-form.selectbox labelName="Type" name="type" required="required" col="col-md-12" class="selectpicker" onchange="setItemType(this.value)">
                        <option value="1">Divider</option>
                        <option value="2">Module/Item</option>
                    </x-form.selectbox>
                    
                    <div class="divider_fields d-none col-md-12 px-0">
                        <x-form.textbox labelName="Divider Title" name="divider_title" required="required" col="col-md-12" placeholder="Enter Divider Title"/>
                    </div>
                    <div class="item_fields d-none col-md-12 px-0">
                        <x-form.textbox labelName="Module Name" name="module_name" required="required" col="col-md-12" placeholder="Enter Module Name"/>
                        <x-form.textbox labelName="URL" name="url" col="col-md-12" placeholder="Enter Module URL"/>


                        <div class="form-group col-md-12">
                            <label for="icon_class">Font Icon class for the Module <a href="https://fontawesome.com">(Use a Fontawesome font class)</a></label>
                            <input type="text" class="form-control" name="icon_class" id="icon_class" placeholder="Enter Icon Class Name">
                        </div>
                        <x-form.selectbox labelName="Open In" name="target" col="col-md-12" class="selectpicker">
                            <option value="_self">Same Tab</option>
                            <option value="_blank">New Tab</option>
                        </x-form.selectbox>
                    </div>
                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>