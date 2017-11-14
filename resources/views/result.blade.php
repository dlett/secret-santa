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
                    {{ $member->group->name }}
                </h1>
                <h2 class="subtitle">
                    Results for {{ $member->name }}
                </h2>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="columns">
            <div class="column is-half is-primary centered is-narrow-mobile">
                    <h1 class="title">You are santa for {{ $member->connection->name }}</h1>
            </div>
        </div>
    </div>
@endsection