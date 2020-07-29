<div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: -17px; bottom: -17px;">
                <div class="simplebar-content" style="padding: 0px; height: auto; overflow: scroll;">
                    @include('components.reports.print.policyMatrix')
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 1430px; height: 981px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
        <div class="simplebar-scrollbar"
             style="width: 853px; transform: translate3d(0px, 0px, 0px); visibility: visible;"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar"
             style="height: 524px; transform: translate3d(0px, 0px, 0px); visibility: visible;"></div>
    </div>
    <div class="table-data-explain">
        <div class="data-explain-item">
            <span class="bg-default"></span>
            <label>Not assigned</label>
        </div>
        <div class="data-explain-item">
            <span class="bg-success"></span>
            <label>Read</label>
        </div>
        <div class="data-explain-item">
            <span class="bg-danger"></span>
            <label>Not read</label>
        </div>
    </div>


@if(request('print'))
    <script>
        window.print();
        setTimeout(function () {
            window.close();
        }, 2000);
    </script>

@endif
