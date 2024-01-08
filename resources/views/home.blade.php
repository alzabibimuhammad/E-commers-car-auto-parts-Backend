<?php
//session_start();
if(session()->get('utype')!=null){
?>
@extends('Auth.layout')
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Home</div>
                  <div class="card-body">

                     @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}

                        </div>
                    @endif
                    <form action="{{ route('edit.profile.customer.seller') }}">
                        @csrf
                        <input type="hidden" name="id" value={{session()->get('id')}}>
                        <input type="submit" name="edit_profile" value="Edit profile">
                    </form>

                    <form action="{{ route('delete.profile.customer.seller') }}">
                        @csrf
                        <input type="hidden" name="id" value={{session()->get('id')}}>
                        <input type="submit" name="delete_profile" value="Delete profile">
                    </form>


                      @if (session()->get('utype')==1)
                      customer
                        {{session()->get('utype')}}
                        id{{session()->get('id')}}

                        <form action="{{ route('show.parts') }}">
                            @csrf
                            <input type="submit" name="showParts" value="Show Parts">
                        </form>

                        @if(isset($allParts))
                                 <form action="{{ route('show.parts') }}">
                                     @csrf
                                     <input type="text" name="search_part" placeholder="search">
                                     <input type="submit" name="search" value="Search">
                                 </form>
                        @foreach ($allParts as $part )
                        <form action="{{ route('add.to.cart') }}">
                            @csrf
                            {{ $part->id }}//{{ $part->name }}//seller{{ $part->seller->name }}//{{ $part->car_id }}//amount->{{ $part->amount }}//cat--{{ $part->category->name }}//{{ $part->price }}//{{ $part->description }}
                            <input type="hidden" name="part_id" value={{ $part->id }}>
                            <input type="hidden" name="customer_id" value={{ session()->get('id') }}>
                            <input type="number" name="amount" style="width:80px" required placeholder="Amount">
                            <input type="submit" name="addtocart" value="Add To Cart" >
                        </form>
                        @endforeach
                        @endif

                        <form action="{{ route('show.cart') }}">
                            @csrf
                            <input type="submit" name="showcart" value="Show Cart">
                        </form>

                        @if(isset($showcart))
                        @foreach ($showcart as $cart )
                        <form action="{{ route('delete.from.cart') }}">
                            @csrf
                            {{ $cart->id }}//{{ $cart->parts->name }}//amount->{{ $cart->amount }}//{{ $cart->seller_id}}//{{ $cart->car_id }}//price->{{ $cart->price }}//totalprice->{{ $cart->totalprice }}
                            <input type="hidden" name="id" value={{ $cart->id }}>
                            <input type="submit" name="addtocart" value="Delete" >
                        </form>
                        @endforeach
                        <form action="{{ route('buy.cart') }}">
                            @csrf
                            <input type="hidden" name="id" value={{session()->get('id')}}>
                            <input type="submit" name="buycart" value="Buy" >
                        </form>
                        @endif

                      @endif


                      @if (session()->get('utype')==2)
                        seller
                      {{session()->get('utype')}}
                      id{{session()->get('id')}}

                      <form action="{{ route('add.part') }}">
                        @csrf
                        <input type="submit" name="add_part" value="Add Part">
                    </form>

                    <form action="{{ route('show.part') }}">
                        @csrf
                        <input type="submit" name="showParts" value="Show Parts">
                    </form>


                    @if(isset($showpart))
                    @foreach ($showpart as $part )
                    <form action="{{ route('delete.part') }}">
                        @csrf
                        {{ $part->id }}//{{ $part->name }}//{{ $part->seller_id }}//{{ $part->car_id }}//{{ $part->amount }}//{{ $part->category_id }}//{{ $part->price }}//{{ $part->description }}
                        <input type="hidden" name="id" value={{ $part->id }}>
                        <input type="submit" name="delete" value="Delete" >
                    </form>
                    <form action="{{ route('edit.part') }}">
                        @csrf
                        <input type="hidden" name="id" value={{ $part->id }}>
                        <input type="submit" name="edit" value="edit" >
                    </form>
                    @endforeach
                    @endif


                    <form action="{{ route('show.delete.part') }}">
                        @csrf
                        <input type="submit" name="showParts" value="Show deleted Parts">
                    </form>
                    @if(isset($showdeletedpart))
                    @foreach ($showdeletedpart as $part )
                    <form action="{{ route('undelete.part') }}">
                        @csrf
                        {{ $part->id }}//{{ $part->name }}//{{ $part->seller_id }}//{{ $part->car_id }}//{{ $part->amount }}//{{ $part->category_id }}//{{ $part->price }}//{{ $part->description }}
                        <input type="hidden" name="id" value={{ $part->id }}>
                        <input type="submit" name="undelete" value="UnDelete" >
                    </form>
                    <form action="{{ route('edit.part') }}">
                        @csrf
                        <input type="hidden" name="id"   value={{ $part->id }}>
                        <input type="submit" name="edit" value="edit" >
                    </form>
                    @endforeach
                    @endif
                    <form action="{{ route('propose.category') }}">
                        @csrf
                        <input type="submit" value="Propose Category">
                    </form>
                    @endif


                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection

<?php }?>
