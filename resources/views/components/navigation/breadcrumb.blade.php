<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-sm-0 fs-18">{{ $current ?? $title }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ $li_1_link ?? 'javascript: void(0);' }}">{{ $li_1 }}</a></li>
                    @if(isset($li_2_link) && isset($li_2))
                        <li class="breadcrumb-item"><a href="{{ $li_2_link ?? 'javascript: void(0);' }}">{{ $li_2 }}</a></li>
                    @endif
                    @if(isset($li_3_link) && isset($li_3))
                        <li class="breadcrumb-item"><a href="{{ $li_3_link ?? 'javascript: void(0);' }}">{{ $li_3 }}</a></li>
                    @endif
                    @if(isset($title))
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>
