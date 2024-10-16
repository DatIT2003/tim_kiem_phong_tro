@extends('layouts.master')

@section('content')

<div class="container bg-light p-4">
    <h2 class="my-4">Kết quả tìm kiếm</h2>

    @if(count($search_motel) > 0)
        <div class="row">
            @foreach($search_motel as $motel)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                @php
                                    $images = json_decode($motel->images, true);
                                @endphp

                                @if(!empty($images) && is_array($images))
                                    <a href="{{ url('phongtro/' . $motel->slug) }}">
                                        <img src="{{ asset('uploads/images/' . $images[0]) }}" class="card-img img-fluid" alt="Image of {{ $motel->title }}" style="max-height: 200px;">
                                    </a>
                                @else
                                    <img src="https://via.placeholder.com/350x200" class="card-img img-fluid" alt="No image available" style="max-height: 200px;">
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ url('phongtro/' . $motel->slug) }}" class="text-decoration-none">{{ $motel->title }}</a>
                                    </h5>
                                    <p class="card-text">Địa chỉ: {{ $motel->address }}</p>
                                    <p class="card-text">Giá: {{ number_format($motel->price) }} VND</p>
                                    <p class="card-text">Điện thoại: {{ $motel->phone }}</p>
                                    <p class="card-text">Diện tích: <strong>{{ $motel->area }} m<sup>2</sup></strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            Không tìm thấy nhà trọ nào phù hợp với yêu cầu của bạn.
        </div>
    @endif
</div>

@endsection