@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Panel -->
        <div class="panel">

            <!-- Panel Header -->
            <div class="panel-header">
                <ul class="panel-tabs withborder text-center">
                    <li class="active">
                        <a href="javascript:;">Search for Influencers</a>
                    </li>
                    <li>
                        <a href="{{ route('collaborate_bookmarks.index') }}">Bookmarked Influencers</a>
                    </li>
                </ul>
            </div> <!-- End Panel Header -->

            <!-- Panel Content -->
            <collaborate-module></collaborate-module>
        </div>

        <!-- Modal: Invite Influencer -->
        <div id="modal-inviteinfluencer" class="sidemodal large" style="display: none">

            <div class="sidemodal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="sidemodal-header-title large">Invite influencer</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="button button-primary button-small text-uppercase">Send Invitation</button>
                        <button class="sidemodal-close normal-flow">
                            <i class="icon-remove"></i>
                        </button>
                    </div>
                </div>
            </div>


            <div class="sidemodal-container">

                <div class="influencer-head smaller">
                    <div class="influencer-pic">
                        <div class="user-avatar"></div>
                    </div>
                    <div class="influencer-data">
                        <p class="title"></p>
                        <p class="desc"></p>
                    </div>
                </div>

                <div class="input-form-group">
                    <label for="#">Invitation to partner with us</label>
                    <select name="" class="input">
                        <option disabled selected>Select idea, concept or campaign</option>
                        <option>Idea 1</option>
                        <option>Idea 2</option>
                        <option>Idea 3</option>
                    </select>
                </div>

                <div class="input-form-group">
                    <label for="#">Content project details</label>
                    <textarea rows="1" class="input input-area" placeholder="Explain task"></textarea>
                </div>

                <div class="input-form-group">
                    <label for="#">What can we offer you</label>
                    <textarea rows="1" class="input input-area" placeholder="Explain your offer"></textarea>
                </div>

                <div class="input-form-group">
                    <label for="#">Reference URL</label>
                    <input type="text" class="input" placeholder="Paste URL">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-form-group">
                            <label for="#">Starts</label>
                            <input type="text" class="input input-calendar" placeholder="Select Date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-form-group">
                            <label for="#">Due</label>
                            <input type="text" class="input input-calendar" placeholder="Select Date">
                        </div>
                    </div>
                </div>

                <div class="fileupload">
                    <i class="icon-add-content picto"></i>
                    <p class="msgtitle">Click to attach one or more documents</p>
                    <input type="file" class="input input-upload">
                </div>

            </div>
        </div> <!-- End Modal: Invite Influencer -->
    </div>
@stop
