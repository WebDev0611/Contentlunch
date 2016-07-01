@extends('2016.layout.master')

@section('content')
<div class="workspace">

  <!-- Pannel Container -->
  <div class="panel clearfix">

    <!-- Main Pane -->
    <div class="panel-main">
      
      <!-- Panel Header -->
      <div class="panel-header">
        <div class="panel-options">
          <div class="row">
            <div class="col-md-6">
              <h4>Content editor</h4>
            </div>
            <div class="col-md-6 text-right">
              <div class="head-actions">
                <button type="button" class="button button-outline-secondary button-small delimited">SAVE</button>

                <button type="button" class="button button-small disabled">PUBLISH</button>

                <div class="btn-group">
                  <button type="button" class="button button-small">SUBMIT</button>
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
      
      <!-- Panel Container -->
      <div class="panel-container padded relative">
        
        <!-- Stages widget -->
        <ul class="list-unstyled list-stages list-stages-side">
          <li><i class="icon-connect"></i></li>
          <li><i class="icon-alert"></i></li>
          <li class="active"><i class="icon-edit"></i></li>
          <li class="active"><i class="icon-idea"></i></li>
        </ul>
        
        
        <div class="inner">
        
          <div class="row">
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">CONTENT TYPE</label>
                <select name="" class="input" >
                  <option selected disabled>Select content type</option>
                  <option>Blog post</option>
                  <option>Article</option>
                  <option>Facebook post</option>
                  <option>Something else</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-form-group input-drop">
                <label for="#">AUTHOR</label>
                <input type="text" class="input" placeholder="Select author" data-toggle="dropdown">
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
            </div>
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">DUE DATE</label>
                <div class="form-suffix">
                  <i class="icon-calendar picto"></i>
                  <input type="text" class="input" placeholder="Select date">
                </div>
              </div>
            </div>
          </div>
          
          <div class="input-form-group">
            <label for="#">TITLE</label>
            <input type="text" class="input input-larger" placeholder="Enter content title">
          </div>
          
          <div class="row">
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">CONTENT DESTINATION</label>
                <select name="" class="input" >
                  <option selected disabled>Select destination</option>
                  <option>Blog post</option>
                  <option>Article</option>
                  <option>Facebook post</option>
                  <option>Something else</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">CONTENT TEMPLATE</label>
                <select name="" class="input" >
                  <option selected disabled>Select template</option>
                  <option>Template 1</option>
                  <option>Template 2</option>
                  <option>Template 3</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <label>&nbsp;</label>
              <button class="button button-outline-secondary button-extend withstarticon"><i class="icon-person-aura"></i>INVITE INFLUENCERS</button>
            </div>
          </div>
          
          <!-- Editor container -->
          <div class="editor" style="background-color: rgba(0,0,0,.1); min-height: 530px; margin-bottom: 25px;"></div>
          
          
          <div class="input-form-group">
            <label for="#">TAGS</label>
            <input type="text" class="input input-larger" placeholder="Enter one or more comma delimited tags">
          </div>
          
          <div class="input-form-group">
            <label for="#">RELATED CONTENT</label>
            <input type="text" class="input input-larger" placeholder="Separate by commas">
          </div>
          
          <div class="input-form-group">
            <label for="#">ATTACHMENTS</label>
                       
            <div class="fileupload">
              <i class="icon-link picto"></i>
              <p class="msgtitle">Click to attach one or more files</p>
              <input type="file" class="input input-upload">
            </div>
          </div>

          
          <!-- Compaign Stage -->
          
          <div class="form-delimiter">
              <span>
                  <em>Campaign Stage</em>
              </span>
          </div>
          
          
          <div class="row">
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">BUYING STAGE</label>
                <select name="" class="input" >
                  <option selected disabled>Select buying stage</option>
                  <option>Stage 1</option>
                  <option>Stage 2</option>
                  <option>Stage 3</option>
                  <option>Stage 4</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-form-group input-drop">
                <label for="#">PERSONA</label>
                <select name="" class="input" >
                  <option selected disabled>Select persona</option>
                  <option>CMO</option>
                  <option>Persona 2</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-form-group">
                <label for="#">CAMPAIGN</label>
                <select name="" class="input" >
                  <option selected disabled>Select campaign</option>
                  <option>Campaign #1</option>
                  <option>Campaign #2</option>
                </select>
              </div>
            </div>
          </div>
          
          
          
          <!-- SEO Information -->
          
          <div class="form-delimiter">
              <span>
                  <em>SEO Information</em>
              </span>
          </div>
          
          <div class="row">
            <div class="col-sm-6">
              <div class="input-form-group">
                <label for="#">META TITLE TAG</label>
                <input type="text" class="input input-larger" placeholder="Enter page title">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="input-form-group input-drop">
                <label for="#">KEYWORDS</label>
                <input type="text" class="input input-larger" placeholder="Separate by commas">
              </div>
            </div>
          </div>
          
          
          <div class="row">
            <div class="col-sm-6">
              <div class="input-form-group">
                <label for="#">META DESCRIPTOR</label>
                <input type="text" class="input input-larger" placeholder="Enter page description">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="input-form-group input-drop">
                <label>&nbsp;</label>
                <div class="row">
                  <div class="col-sm-6">
                    <button class="button button-outline-secondary button-extend withstarticon"><i class="icon-seo-magnifier"></i>SEO CHECK</button>
                    <p class="help-block">Analyze content for prelim SEO score</p>
                  </div>
                  <div class="col-sm-6">
                    <button class="button button-outline-secondary button-extend withstarticon"><i class="icon-view-magnifier"></i>SEARCH PREVIEW</button>
                    <p class="help-block">Analyze content for prelim SEO score</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
          <!-- Image Attachment -->
          
          <div class="form-delimiter">
              <span>
                  <em>Image</em>
              </span>
          </div>
          
          
          <div class="input-form-group">
            <div class="fileupload">
              <i class="icon-content picto"></i>
              <p class="msgtitle">Click to upload one or more images</p>
              <input type="file" class="input input-upload">
            </div>
          </div>
          
          
          <div class="form-delimiter">
              <span>
                  <em>Custom Fields</em>
              </span>
          </div>
          
          
          
          
          
          
          
        </div>
          
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

@stop