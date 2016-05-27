@extends('layouts.default')
@section('content')

    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <h3>WriterAccess Fees</h3>
                <table class='table table-striped table-bordered table-hover'>
                    <tr>
                        <th>Asset type</th>
                        <th>Writer level</th>
                        <th>Wordcount</th>
                        <th>Fee</th>
                        <th style="width: 102px;">&nbsp;</th>
                    </tr>
                    @foreach($writerAccessPrices as $writerAccessPrice)
                        <tr>
                            <td>{{ @$writerAccessPrice->name }}</td>
                            <td>{{ @$writerAccessPrice->writer_level }} Stars</td>
                            <td>{{ @$writerAccessPrice->wordcount }}</td>
                            <td>${{ number_format(@$writerAccessPrice->fee, 2) }}</td>
                            <td>
                                <a href='writerAccessPrices/{{$writerAccessPrice->id}}/edit' class='btn btn-primary'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

                                {{ Form::open(['url'=>'writerAccessPrices/'.$writerAccessPrice->id, 'method'=>'delete', 'style'=>'display:inline;']) }}
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@stop
