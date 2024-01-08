@extends('Auth.layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    You are Logged In
                    @if (session()->get('utype')==0)
                    <form action="{{ route('vaildseller') }}">
                        @csrf
                        <input type="submit" name="vaildsellers" value="vaildsellers">
                    </form>
                    @if(isset($data))
                    @foreach ($data as $d  )
                    <form action="{{ route('approve.seller') }}">
                        @csrf
                        {{ $d->id }}//{{ $d->name }}//{{ $d->email }}
                        <input type="hidden" name="id" value={{ $d->id }}>
                        <input type="submit" name="approve" value="approve" ><br>
                    </form>
                    @endforeach
                    @endif

                    <form action="{{ route('show.seller') }}">
                        @csrf
                        <input type="submit" name="sellers" value="sellers">
                    </form>
                    @if(isset($show_sellers))
                    @foreach ($show_sellers as $show_seller)

                    <form action="{{ route('delete.seller') }}">
                        @csrf
                        {{ $show_seller->id }}//{{ $show_seller->name }}//{{ $show_seller->email }}//{{ $show_seller->address }}//{{ $show_seller->phone }}
                        <input type="hidden" name="id" value={{ $show_seller->id }}>
                        <input type="submit" name="delete" value="delete" >
                    </form>
                    <form action="{{ route('ban.seller') }}">
                        @csrf
                        <input type="hidden" name="id" value={{ $show_seller->id }}>
                        <input type="submit" name="ban" value="Ban" >
                    </form>
                    @endforeach
                    @endif

                    <form action="{{ route('show.baned.seller') }}">
                        @csrf
                        <input type="submit" name="ban_seller" value="Baned Seller">
                    </form>

                    @if(isset($baned_seller))
                    @foreach ($baned_seller as $b_s )
                    <form action="{{ route('unban.seller') }}">
                        @csrf
                        {{ $b_s->id }}//{{ $b_s->name }}//{{ $b_s->email }}//{{ $b_s->address }}//{{ $b_s->phone }}
                        <input type="hidden" name="id" value={{ $b_s->id }}>
                        <input type="submit" name="unban" value="unban" >
                    </form>
                    @endforeach
                    @endif

                    <form action="{{ route('show.customer') }}">
                        @csrf
                        <input type="submit" name="show_customer" value="Customers">
                    </form>
                    @if(isset($show_customers))
                    @foreach ($show_customers as $customer )
                    <form action="{{ route('delete.customer') }}">
                        @csrf
                        {{ $customer->id }}//{{ $customer->name }}//{{ $customer->email }}//{{ $customer->address }}//{{ $customer->phone }}
                        <input type="hidden" name="id" value={{ $customer->id }}>
                        <input type="submit" name="delete" value="delete" >
                    </form>
                    <form action="{{ route('ban.customer') }}">
                        @csrf
                        <input type="hidden" name="id" value={{ $customer->id }}>
                        <input type="submit" name="ban" value="ban" >
                    </form>
                    @endforeach
                    @endif


                    <form action="{{ route('show.baned.customers') }}">
                        @csrf
                        <input type="submit" name="baned_customer" value="Baned Customer">
                    </form>

                    @if(isset($baned_customer))
                    @foreach ($baned_customer as $b_c )
                    <form action="{{ route('unban.customer') }}">
                        @csrf
                        {{ $b_c->id }}//{{ $b_c->name }}//{{ $b_c->email }}//{{ $b_c->address }}//{{ $b_c->phone }}
                        <input type="hidden" name="id" value={{ $b_c->id }}>
                        <input type="submit" name="unban" value="unban" >
                    </form>
                    @endforeach
                    @endif

                    <form action="{{ route('show.category') }}">
                        @csrf
                        <input type="submit" name="show_category" value="Categories">
                    </form>
                    @if(isset($category))
                    @foreach ($category as $categorys )

                    @if($categorys->deleted_at == null)
                    <form action="{{ route('delete.category') }}">
                        @csrf
                        {{ $categorys->id }}//{{ $categorys->name }}//{{ $categorys->description }}
                        <input type="hidden" name="id" value={{ $categorys->id }}>
                        <input type="submit" name="delete" value="Delete" >
                    </form>
                    @endif
                    @if($categorys->deleted_at != null)
                    <form action="{{ route('undelete.category') }}">
                        @csrf
                        {{ $categorys->id }}//{{ $categorys->name }}//{{ $categorys->description }}
                        <input type="hidden" name="id" value={{ $categorys->id }}>
                        <input type="submit" name="undelete" value="UnDelete" >
                    </form>
                    @endif

                    <form action="{{ route('edit.category') }}">
                        @csrf
                        <input type="hidden" name="id" value={{ $categorys->id }}>
                        <input type="submit" name="edit" value="edit" >
                    </form>
                    @endforeach
                    @endif



                    <form action="{{ route('add.category') }}">
                        @csrf
                        <input type="submit" name="add_category" value="Add Category">
                    </form>


                    <form action="{{ route('show.propose.category') }}">
                        @csrf
                        <input type="submit" name="showproposecategory" value="show propose category">
                    </form>
                    @if(isset($proposecategory))
                    @foreach ($proposecategory as $d  )
                    <form action="{{ route('approve.category') }}">
                        @csrf
                        {{ $d->id }}//{{ $d->name }}//{{ $d->description }}
                        <input type="hidden" name="id" value={{ $d->id }}>
                        <input type="hidden" name="name" value={{ $d->name }}>
                        <input type="hidden" name="description" value={{ $d->description }}>
                        <input type="submit" name="approve" value="approve" ><br>
                    </form>
                    @endforeach
                    @endif

                    @endif






                </div>
            </div>
        </div>
    </div>
</div>
@endsection
