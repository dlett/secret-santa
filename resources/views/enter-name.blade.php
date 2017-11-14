@extends('layout')

@section('content')
<style type="text/css">
    .centered {
        margin: auto;
    }

    .title {
        margin-top: 50px;
    }
</style>

<section class="hero is-danger">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                {{ $group->name }}
            </h1>
            <h2 class="subtitle">
                Secret Santa!
            </h2>
        </div>
    </div>
</section>

<div class="container">
    <div class="columns">
        <div class="column is-half is-primary centered">
            <h1 class="title">Enter your name</h1>
            <form method="post" action="/result">
                <div class="field">
                    <input type="text" class="input" name="name" placeholder="John">
                </div>
                <input type="submit" value="Find your match!" class="button">
            </form>
        </div>
    </div>
</div>

@endsection
