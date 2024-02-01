@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.Dashboard')}}</title>
@endsection
@section('admin-content')

    <!-- Modal -->
    @foreach ($todayOrders as $index => $order)
        <div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog"
             aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('admin.Order Status')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('admin.Payment')}}</label>
                                    <select name="payment_status" id="" class="form-control">
                                        <option
                                            {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        <option
                                            {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('admin.Order')}}</label>
                                    <select name="order_status" id="" class="form-control">
                                        <option
                                            {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        <option
                                            {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                        <option
                                            {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                                        <option
                                            {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                        <option
                                            {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger"
                                            data-dismiss="modal">{{__('admin.Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('admin.Update Status')}}</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    @endforeach

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
        }
    </script>
@endsection
