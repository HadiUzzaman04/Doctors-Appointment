<ol class="dd-list">
    @forelse ($menu->menuItems as $item)
        <li class="dd-item" data-id="{{ $item->id }}">
            <div class="pull-right item_action">
                @if (permission('menu-module-delete'))
                <button type="button" class="btn btn-danger btn-sm btn-elevate btn-icon float-right delete_data" 
                data-id="{{ $item->id }}" data-name="{{ $item->type == 1 ? $item->divider_title : $item->module_name }}">
                    <i class="fas fa-trash"></i>
                </button>
                @endif
                @if (permission('menu-module-edit'))
                <button data-id="{{ $item->id }}" class="btn btn-primary btn-sm btn-elevate btn-icon float-right edit_data mr-2"><i class="fas fa-edit"></i></button>
                @endif
            </div>
            <div class="dd-handle">
                @if ($item->type == 1)
                    <strong>Divider: {{ $item->divider_title }}</strong>
                @else
                    <span> <i class="{{ $item->icon_class }} mr-2"></i> {{ $item->module_name }}</span> <small class="url">{{ $item->url }}</small>
                @endif
            </div>
            @if (!$item->children->isEmpty())
                <ol class="dd-list">
                    @forelse ($item->children as $subitem)
                        <li class="dd-item" data-id="{{ $subitem->id }}">
                            <div class="pull-right item_action">
                                @if (permission('menu-module-delete'))
                                <button type="button" class="btn btn-danger btn-sm btn-elevate btn-icon float-right delete_data" 
                                data-id="{{ $subitem->id }}" data-name="{{ ($subitem->type == 1) ? $subitem->divider_title : $subitem->module_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @if (permission('menu-module-edit'))
                                <button data-id="{{ $subitem->id }}" class="btn btn-primary btn-sm btn-elevate btn-icon float-right edit_data mr-2"><i class="fas fa-edit"></i></button>
                                @endif
                            </div>
                            <div class="dd-handle">
                                @if ($subitem->type == 1)
                                    <strong>Divider: {{ $subitem->divider_title }}</strong>
                                @else
                                    <span><i class="{{ $subitem->icon_class }} mr-2"></i> {{ $subitem->module_name }}</span> <small class="url">{{ $subitem->url }}</small>
                                @endif
                            </div>
                            @if (!$subitem->children->isEmpty())
                            <ol class="dd-list">
                                @forelse ($subitem->children as $sub_subitem)
                                    <li class="dd-item" data-id="{{ $sub_subitem->id }}">
                                        <div class="pull-right item_action">
                                            @if (permission('menu-module-delete'))
                                            <button type="button" class="btn btn-danger btn-sm btn-elevate btn-icon float-right delete_data" 
                                            data-id="{{ $sub_subitem->id }}" data-name="{{ ($sub_subitem->type == 1) ? $sub_subitem->divider_title : $sub_subitem->module_name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                            @if (permission('menu-module-edit'))
                                            <button data-id="{{ $sub_subitem->id }}" class="btn btn-primary btn-sm btn-elevate btn-icon float-right edit_data mr-2"><i class="fas fa-edit"></i></button>
                                            @endif
                                        </div>
                                        <div class="dd-handle">
                                            @if ($sub_subitem->type == 1)
                                                <strong>Divider: {{ $sub_subitem->divider_title }}</strong>
                                            @else
                                                <span><i class="{{ $sub_subitem->icon_class }} mr-2"></i> {{ $sub_subitem->module_name }}</span> <small class="url">{{ $sub_subitem->url }}</small>
                                            @endif
                                        </div>
                                        
                                    </li>
                                @empty
                                    <div class="text-center">
                                        <strong>No menu item found</strong>
                                    </div>
                                @endforelse
                            </ol>
                        @endif
                        </li>
                    @empty
                        <div class="text-center">
                            <strong>No menu item found</strong>
                        </div>
                    @endforelse
                </ol>
            @endif
        </li>
    @empty
        <div class="text-center">
            <strong>No menu item found</strong>
        </div>
    @endforelse
</ol>