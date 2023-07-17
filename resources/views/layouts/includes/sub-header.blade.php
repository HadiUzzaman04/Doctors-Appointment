<div class="subheader py-2  subheader-solid " id="kt_subheader">
    <div  class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-right flex-wrap mr-1">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"> {{ $page_title }}</h5>
        </div>
        
        <div class="d-flex align-items-center pt-4">
            @if (request()->is('/') || request()->is('asm/dashboard'))
            <div class="filter-toggle btn-group float-right" style="margin-bottom: 1rem;margin-right: 1rem;">
                <div class="btn btn-primary btn-sm today-btn data-btn active" data-start_date="{{ date('Y-m-d') }}" data-end_date="{{ date('Y-m-d') }}">Today</div>
                <div class="btn btn-primary btn-sm week-btn data-btn" data-start_date="{{ date('Y-m-d',strtotime('-7 day')) }}" data-end_date="{{ date('Y-m-d') }}">This Week</div>
                <div class="btn btn-primary btn-sm month-btn data-btn" data-start_date="{{ date('Y-m').'-01' }}" data-end_date="{{ date('Y-m-d') }}">This Month</div>
                <div class="btn btn-primary btn-sm year-btn data-btn" data-start_date="{{ date('Y').'-01-01' }}" data-end_date="{{ date('Y').'-12-31' }}">This Year</div>
            </div>
            @endif
            

            <ol class="breadcrumb float-right pull-right">
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                @if (!empty($breadcrumb))
                    @foreach ($breadcrumb as $item)
                        @if(!isset($item['link']))
                        <li class="active">{{ $item['name'] }}</li>
                        @else 
                        <li><a href="{{ $item['link'] }}">{{ $item['name'] }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>
        </div>
    </div>
</div>