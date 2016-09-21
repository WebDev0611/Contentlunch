@extends('layouts.master')

@section('content')
<div class="workspace">

  <!-- Pannel Container -->
  <div class="panel clearfix">

    <!-- Main Pane -->
    <div class="panel-main">
      
      <!-- Panel Header -->
      <div class="panel-header" id="create-campaign-form">
        <div class="panel-options">
          <div class="row">
            <div class="col-md-6">
              <h4>Campaign editor</h4>
            </div>
            <div class="col-md-6 text-right">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="head-actions">
                <button type="button" class="button button-outline-secondary button-small delimited" id="save-campaign-button">SAVE</button>

                <button type="button" class="button button-small disabled">LAUNCH</button>

                <div class="btn-group">
                  <button type="button" class="button button-small">DONE</button>
                  <button type="button" class="button button-small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Preview</a></li>
                    <li><a href="#">Export to PDF</a></li>
                    <li><a href="#">Park</a></li>
                    <li><a href="#">Delete</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End Panel Header -->
      
      <!-- Content Tabs -->
      <ul class="panel-tabs centered even withborder">
        <li class="active">
          <a href="#contenttab-content" role="tab" data-toggle="tab">Content</a>
        </li>
        <li>
          <a href="#contenttab-campaign" role="tab" data-toggle="tab">Campaign Info</a>
        </li>
      </ul>
      
      
      <!-- Panel Container -->
      <div class="panel-container nopadding relative">
        
  
          
          <!-- Tab Content -->
          <div class="tab-content">
            
            
            <!-- Tab1: Content -->
            <div role="tabpanel" class="tab-pane active" id="contenttab-content">
              
              
              <!-- Panel Ready to be Published -->
              <div class="content-panel">
                <p class="panel-head"><span class="picto"><i class="icon-alert"></i></span> READY TO BE PUBLISHED</p>
              
                <ul class="list-unstyled list-content bordered">
                  <li class="list-alert">
                    <div class="list-avatar">
                      <div class="user-avatar">
                        <img src="/images/avatar.jpg">
                      </div>
                    </div>
                    <div class="list-title">
                      <a href="#">
                        <p>Post 16 social postings on woman rights and movements around the world</p>
                        <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>PUBLISH</strong></p>
                      </a>
                    </div>
                    <div class="list-team">

                    </div>
                    <div class="list-type">
                      <i class="icon-type-blog"></i>
                    </div>
                  </li>
                  <li>
                    <div class="list-avatar">
                      <div class="user-avatar">
                        <img src="/images/avatar.jpg">
                      </div>
                    </div>
                    <div class="list-title">
                      <a href="#">
                        <p>Write blog post on online banking</p>
                        <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>PUBLISH</strong></p>
                      </a>
                    </div>
                    <div class="list-type">
                      <i class="icon-type-facebook"></i>
                    </div>
                  </li>
                  <li>
                    <div class="list-avatar">
                      <div class="user-avatar">
                        <img src="/images/avatar.jpg">
                      </div>
                    </div>
                    <div class="list-title">
                      <a href="#">
                        <p>Post 16 social postings on woman rights and movements around the world</p>
                        <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>PUBLISH</strong></p>
                      </a>
                    </div>
                    <div class="list-team">

                    </div>
                    <div class="list-type">
                      <i class="icon-type-blog"></i>
                    </div>
                  </li>
                </ul>
                

              </div>
              
              
              <!-- Panel Being Edited / Written -->
              <div class="content-panel">
                <p class="panel-head"><span class="picto"><i class="icon-edit"></i></span> BEING EDITED / WRITTEN</p>
              
                <ul class="list-unstyled list-content bordered">
                  <li class="list-external">
                    <div class="progressbar" style="width: 35%"></div>
                    <div class="list-avatar">
                      <div class="user-avatar">
                        <img src="/images/avatar.jpg">
                      </div>
                    </div>
                    <div class="list-title">
                      <a href="#">
                        <p>Write blog post on online banking <span class="badge-external">External</span></p>
                        <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>EDIT</strong></p>
                      </a>
                    </div>
                    <div class="list-team">

                    </div>
                    <div class="list-type">
                      <i class="icon-type-blog"></i>
                    </div>
                  </li>
                  <li>
                    <div class="progressbar" style="width: 85%"></div>
                    <div class="list-avatar">
                      <div class="user-avatar">
                        <img src="/images/avatar.jpg">
                      </div>
                    </div>
                    <div class="list-title">
                      <a href="#">
                        <p>16 social postings on woman rights and movements around the world</p>
                        <p class="small">15 DAYS AGO <span class="delimit">&middot;</span>  NEXT TASK: <strong>EDIT</strong></p>
                      </a>
                    </div>
                    <div class="list-type">
                      <i class="icon-type-facebook"></i>
                    </div>
                  </li>
                </ul>
                

              </div>
              
              
              
            </div>

            
            <!-- Tab2: Campaign Info -->
            <div role="tabpanel" class="tab-pane" id="contenttab-campaign">
              
              <div class="inner toppadded">
              
                <div class="input-form-group">
                  <label for="#">CAMPAIGN TITLE</label>
 {!! Form::text('campaign-title', @isset($campaign)? $campaign->title : '', array('placeholder' => 'Enter campaign title', 'class' => 'input input-larger form-control', 'id' => 'campaign-title')) !!}
                </div>

                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">START DATE</label>
                      <div class="form-suffix">
                        <i class="icon-calendar picto"></i>
                        {!! Form::text('start-date', @isset($campaign)? $campaign->start_date : '', array('class' => ' input form-control', 'id' => 'start-date')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">END DATE</label>
                      <div class="form-suffix">
                        <i class="icon-calendar picto"></i>
                        {!! Form::text('end-date', @isset($campaign)? $campaign->end_date : '', array('class' => ' input form-control', 'id' => 'end-date')) !!}
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">OTHER DATE 1</label>
                      <div class="form-suffix">
                        <i class="icon-calendar picto"></i>
                        <input type="text" class="input"  id="other-date-1" placeholder="Select date">
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">OTHER DATE 2</label>
                      <div class="form-suffix">
                        <i class="icon-calendar picto"></i>
                        <input type="text" class="input" id="other-date-2" placeholder="Select date">
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">CAMPAIGN TYPE</label>
                      {!! Form::select('campaign-type', $campaigntypedd, @isset($campaign)? $campaign->campaign_type_id : ''  , array('class' => 'input selectpicker form-control', 'id' => 'campaign-types', 'title' => 'Choose Campaign Type')) !!}
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">CAMPAIGN BUDGET</label>
                      <input type="text" name="campaign-budget" class="input input-larger" placeholder="Enter budget in USD" value="">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-form-group">
                      <label for="#">STATUS</label>
                      <select name="" class="input" >
                        <option selected disabled>Set campaign status</option>
                        <option>Active</option>
                        <option>Paused</option>
                        <option>Inactive</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div class="input-form-group">
                  <label for="#">CAMPAIGN DESCRIPTION</label>
                   {!! Form::textarea('campaign-description', @isset($campaign)? $campaign->description : '', array('placeholder' => 'Enter Campaign Description', 'class' => 'input input-larger form-control', 'id' => 'campaign-description')) !!}
                </div>

                <div class="input-form-group">
                  <label for="#">CAMPAIGN GOALS</label>
                  {!! Form::textarea('campaign-goals', @isset($campaign)? $campaign->goals : '', array('placeholder' => 'Enter Campaign Goals', 'class' => 'input input-larger form-control', 'id' => 'campaign-goals')) !!}
                </div>

                <div class="input-form-group">
                  <label for="#">TAGS</label>
                  <input type="text" name="campaign-tags" class="input input-larger" placeholder="Enter one or more tags">
                </div>



                <!-- Attachments -->

                <div class="form-delimiter">
                    <span>
                        <em>Attachments</em>
                    </span>
                </div>


                <div class="input-form-group">

                  <div class="fileupload">
                    <i class="icon-link picto"></i>
                    <p class="msgtitle">Click to attach one or more files</p>
                    <input type="file" class="input input-upload">
                  </div>
                </div>
                
               </div>
              
            </div>
            
          </div> <!-- End Tab Content -->

          
      </div>  <!-- End Panel Container -->
      
    </div> <!-- End Main Pane -->

    <!-- Side Pane -->
    <aside class="panel-sidebar">
      
      <div class="panel-header">
        <ul class="panel-tabs withborder">
          <li class="active">
            <a href="#sidetab-tasks" role="tab" data-toggle="tab">Content Tasks</a>
          </li>
          <li>
            <a href="#sidetab-activity" role="tab" data-toggle="tab">Activity</a>
          </li>
          <li>
            <a href="#sidetab-history" role="tab" data-toggle="tab">History</a>
          </li>
        </ul>
      </div>
      
      
      
      
      
      <div class="tab-content">
        
        <!-- Content Task Panel -->
        <div role="tabpanel" class="sidepanel tab-pane active" id="sidetab-tasks">

          <div class="content-tasks-box-container">

            <div class="twocols">
              <p class="intro">Tasks to be completed on this content piece</p>
              <a href="#newtask" class="btn button-text withendicon" data-toggle="collapse">NEW TASK<i class="icon-add"></i></a>
            </div>
            
            
            <!-- New Task -->
            <div class="task new collapse" id="newtask">

              <p class="title">New task</p>

              <div class="task-content">
                
                <div class="input-form-group">
                  <label for="#">Task name</label>
                  <input type="text" class="input" placeholder="Enter name">
                </div>
                

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>
                
                <div class="input-form-group">
                  <label for="#">Completed by</label>
                  <select name="" class="input">
                    <option disabled selected>Please select</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                  </select>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>

            </div> <!-- End New Task -->
            

            <h5>Content Creation</h5>

            <!-- Task -->
            <div class="task active">

              <div class="body">

                <div class="checkcircle"><i class="icon-check-light"></i></div>

                <div class="user-avatar">
                  <img src="/images/avatar.jpg" alt="#">
                </div>

                <p class="title">Assign Author</p>
                <p>7 days before content Due Date</p>
              </div>

              <div class="foot">
                <div class="task-actions">
                  <ul class="list-inline list-actions">
                    <li><a href="#task1" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                    <li><a href="#task1" data-toggle="collapse"><i class="icon-schedule"></i></a></li>
                    <li><a href="#"><i class="icon-trash"></i></a></li>
                  </ul>
                </div>
              </div>

              <div class="task-content collapse" id="task1">

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>

            </div>


            <!-- Task -->
            <div class="task">

              <div class="body">
                <div class="checkcircle"><i class="icon-check-light"></i></div>

                <div class="user-avatar">
                  <img src="/images/avatar.jpg" alt="#">
                </div>

                <p class="title">Submit Content</p>
                <p>5 days before content Due Date</p>
              </div>


              <div class="task-actions">
                <ul class="list-inline list-actions">
                  <li><a href="#task2" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                  <li><a href="#task2" data-toggle="collapse"><i class="icon-schedule"></i></a></li>
                  <li><a href="#"><i class="icon-trash"></i></a></li>
                </ul>
              </div>

              <div class="task-content collapse" id="task2">

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>


            </div>


            <!-- Task -->
            <div class="task">

              <div class="body">
                <div class="checkcircle"><i class="icon-check-light"></i></div>

                <div class="user-avatar">
                  <img src="/images/avatar.jpg" alt="#">
                </div>

                <p class="title">Review and Edit Content</p>
                <p>2 days before content Due Date</p>
              </div>

              <div class="task-actions">
                <ul class="list-inline list-actions">
                  <li><a href="#task3" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                  <li><a href="#task3" data-toggle="collapse"><i class="icon-schedule"></i></a></li>
                  <li><a href="#"><i class="icon-trash"></i></a></li>
                </ul>
              </div>

              <div class="task-content collapse" id="task3">

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>

            </div>


            <!-- Task -->
            <div class="task">

              <div class="body">
                <div class="checkcircle"><i class="icon-check-light"></i></div>

                <div class="user-avatar">
                  <img src="/images/avatar.jpg" alt="#">
                </div>

                <p class="title">Publish Content</p>
              </div>

              <div class="task-actions">
                <ul class="list-inline list-actions">
                  <li><a href="#task4" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                  <li><a href="#task4" data-toggle="collapse"><i class="icon-schedule"></i></a></li>
                  <li><a href="#"><i class="icon-trash"></i></a></li>
                </ul>
              </div>

              <div class="task-content collapse" id="task4">

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>

            </div>


            <h5>Content Promotion</h5>

            <!-- Task -->
            <div class="task">

              <div class="body">
                <div class="checkcircle"><i class="icon-check-light"></i></div>

                <div class="user-avatar">
                  <img src="/images/avatar.jpg" alt="#">
                </div>

                <p class="title">Promote Content</p>
                <p>2 days after content Due Date</p>

              </div>

              <div class="task-actions">
                <ul class="list-inline list-actions">
                  <li><a href="#task5" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                  <li><a href="#task5" data-toggle="collapse"><i class="icon-schedule"></i></a></li>
                  <li><a href="#"><i class="icon-trash"></i></a></li>
                </ul>
              </div>

              <div class="task-content collapse" id="task5">

                <div class="form-group">
                  <fieldset class="form-fieldset clearfix">
                    <legend class="form-legend">Assigned</legend>
                    <ul class="images-list pull-left">
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                      <li>
                        <img src="/images/avatar.jpg" alt="#">
                      </li>
                    </ul>
                    <div class="dropdown pull-right">
                      <button type="button" class="button button-action large" data-toggle="dropdown">
                        <i class="icon-add-circle"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header-secondary">
                          <span class="dropdown-header-secondary-text">
                            Select team member
                          </span>
                          <button class="button button-micro pull-right text-uppercase">
                            Submit
                          </button>
                        </li>
                        <li>
                          <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                        </li>
                        <li>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                          <label for="Friend" class="checkbox-image">
                            <input id="Friend" type="checkbox">
                            <span>
                              <img src="/images/avatar.jpg" alt="#">
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </fieldset>
                </div>

                <div class="input-form-group">
                  <label for="#">Deadline</label>
                  <div class="form-suffix">
                    <i class="icon-calendar picto"></i>
                    <input type="text" class="input" placeholder="Due date and time">
                  </div>
                </div>

                <div class="button-bar">
                  <button type="button" class="button button-small button-outline-secondary">CANCEL</button>
                  <button type="button" class="button button-small">SAVE</button>
                </div>

              </div>

            </div>

          </div>

        </div> <!-- End Content Task Panel -->
        
        
        <!-- Tab 2: Activity -->
        <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-activity">
          
          <div class="pane-activity">
            
            <div class="plan-activity-box-container">
              <div class="plan-activity-box-img">
                <img src="/images/avatar.jpg" alt="#">
              </div>
              <div class="plan-activity-box">
                <span class="plan-activity-title">
                  <a href="#">Jane</a> commented on
                  <a href="#"> Write blog post</a> on
                  <a href="#">online banking</a>
                </span>
                <p class="plan-activity-text">
                  Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                  Etiam eget dolor...
                </p>
              </div>
            </div>
            <div class="plan-activity-box-container">
              <div class="plan-activity-box-img">
                <img src="/images/avatar.jpg" alt="#">
              </div>
              <div class="plan-activity-box">
                <span class="plan-activity-title">
                  <a href="#">Jane</a> commented on
                  <a href="#"> Write blog post</a> on
                  <a href="#">online banking</a>
                </span>
                <p class="plan-activity-text">
                  Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                  Etiam eget dolor...
                </p>
              </div>
            </div>
            <div class="plan-activity-box-container">
              <div class="plan-activity-box-icon">
                <i class="icon-edit"></i>
              </div>
              <div class="plan-activity-box">
                <span class="plan-activity-title">
                  <a href="#">Jane</a> commented on
                  <a href="#"> Write blog post</a> on
                  <a href="#">online banking</a>
                </span>
              </div>
            </div>
            <div class="plan-activity-box-container">
              <div class="plan-activity-box-img">
                <img src="/images/avatar.jpg" alt="#">
              </div>
              <div class="plan-activity-box">
                <span class="plan-activity-title">
                  <a href="#">Jane</a> commented on
                  <a href="#"> Write blog post</a> on
                  <a href="#">online banking</a>
                </span>
                <p class="plan-activity-text">
                  Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                  Etiam eget dolor...
                </p>
                <div class="plan-activity-dropdown">
                  <button type="button" class="button button-action" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                      <a href="#">Write It</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
          </div>
          
        </div>
        
        <!-- Tab 3: History -->
        <div class="sidepanel tab-pane" role="tabpanel" id="sidetab-history">
          <p>This is history</p>
        </div>
        
        
      </div>
      
      
      
      
      <!-- Collaborators / Guests tabs -->
      <div class="sidepanel-head">
        <ul class="panel-tabs withborder withtopborder">
          <li class="active">
            <a href="#sidetab-collaborators" data-toggle="tab">Collaborators</a>
          </li>
          <li>
            <a href="#sidetab-guests" data-toggle="tab">Guests</a>
          </li>
          <li class="tablink">
            <a href="#" class="btn button-text"><i class="icon-add-person"></i></a>
          </li>
        </ul>
      </div>
      
      
      <div class="tab-content">
        
        <!-- Tab 1: Collaborators -->
        <div class="sidepanel nopadding tab-pane active" id="sidetab-collaborators">

          <div class="sidepanel-body">
            <div class="pane-users">
              <ul class="list-unstyled list-users">
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Jason Simmons</p>
                    <p class="email">jasonsimm@google.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Emily Blunt</p>
                    <p class="email">emilyblunt@yahoo.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Johan Rostock</p>
                    <p class="email">jrock@google.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Annie Sox</p>
                    <p class="email">asox@yahoo.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Jason Simmons</p>
                    <p class="email">jasonsimm@google.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Emily Blunt</p>
                    <p class="email">emilyblunt@yahoo.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Johan Rostock</p>
                    <p class="email">jrock@google.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Annie Sox</p>
                    <p class="email">asox@yahoo.com</p>
                  </a>
                </li>
              </ul>
            </div>
          </div>

        </div> <!-- Tab 1: Collaborators -->
        
        
        <!-- Tab 2: Guests -->
        <div class="sidepanel nopadding tab-pane" id="sidetab-guests">
          
          <div class="sidepanel-body">
            <div class="pane-users">
              <ul class="list-unstyled list-users">
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Johan Rostock</p>
                    <p class="email">jrock@google.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Annie Sox</p>
                    <p class="email">asox@yahoo.com</p>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="user-avatar">
                      <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <p class="title">Jason Simmons</p>
                    <p class="email">jasonsimm@google.com</p>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          
        </div>
        
        
      </div>
      
      
      
      
    </aside> <!-- End Side Pane -->

  </div>  <!-- End Panel Container -->

</div>
<script>
  var campaign_types = {!! $campaign_types !!};
  var campaign = {!! $campaign->toJson() !!};
</script>
@stop

@section('scripts')
<script src="/js/campaign.js"></script>
@stop