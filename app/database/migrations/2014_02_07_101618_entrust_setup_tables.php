<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Creates the roles table
    Schema::create('roles', function($table)
    {
      $table->increments('id')->unsigned();
      $table->string('name');
      $table->string('display_name');
      $table->boolean('global')->default(false);
      $table->boolean('deletable')->default(false);
      $table->boolean('builtin')->default(false);
      $table->integer('status')->default(1);
      $table->integer('account_id')->nullable();
      $table->timestamps();
    });

    // Creates the assigned_roles (Many-to-Many relation) table
    Schema::create('assigned_roles', function($table)
    {
      $table->increments('id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->integer('role_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users'); // assumes a users table
      $table->foreign('role_id')->references('id')->on('roles');
    });

    // Creates the permissions table
    Schema::create('permissions', function($table)
    {
      $table->increments('id')->unsigned();
      $table->string('name');
      $table->string('display_name');
      $table->string('module');
      $table->string('type');
      $table->timestamps();
    });

    // Creates the permission_role (Many-to-Many relation) table
    Schema::create('permission_role', function($table)
    {
      $table->increments('id')->unsigned();
      $table->integer('permission_id')->unsigned();
      $table->integer('role_id')->unsigned();
      $table->foreign('permission_id')->references('id')->on('permissions'); // assumes a users table
      $table->foreign('role_id')->references('id')->on('roles');
    });

    // Create builtin roles: Global Admin, Site Admin, etc...
    $roles = [];
    foreach ([
      ['global_admin', 'Global Admin', true, false, false, 1],
      ['site_admin', 'Site Admin', false, false, false, 1],
      ['manager', 'Manager / Director', false, false, true, 1],
      ['creator', 'Creator / Author', false, false, true, 1],
      ['client', 'Client', false, false, true, 1],
      ['editor', 'Editor', false, false, true, 1]
    ] as $row) {
      $role = new Role;
      $role->name = $row[0];
      $role->display_name = $row[1];
      $role->global = $row[2];
      $role->deletable = $row[3];
      $role->builtin = $row[4];
      $role->status = $row[5];
      $role->save();
      $roles[$row[0]] = $role;
      echo "Created Builtin role: ". $role->name . PHP_EOL;
    }

    // Add application permissions
    $permissions = [
      // Key, Display name, Default roles to assign permissions to
      // Create
      ['create_execute_content_own', 'Create / edit own content',
        ['manager', 'creator', 'editor', 'client']],
      ['create_view_content_other_unapproved', 'Access other User\'s Content, Not Yet Approved',
        ['manager', 'creator', 'editor', 'client']],
      ['create_edit_content_other_unapproved', 'Access other User\'s Content, Not Yet Approved',
        ['manager', 'editor']],
      ['create_view_content_other', 'Access other User\'s Completed Content',
        ['manager', 'creator', 'editor', 'client']],
      ['create_edit_content_other', 'Access other User\'s Completed Content',
        ['manager']],
      ['create_execute_ideas_own', 'Create / edit own ideas',
        ['manager', 'creator', 'editor', 'client']],
      ['create_view_ideas_other', 'Access Ideas Created by Other Users',
        ['manager', 'editor']],
      ['create_edit_ideas_other', 'Access Ideas Created by Other Users',
        ['manager', 'editor']],
      // Collaborate
      ['collaborate_execute_sendcontent', 'Send a piece of content to internal team',
        ['manager', 'creator', 'editor', 'client']],
      ['collaborate_execute_feedback', 'Give ideas and feedback to other team members on a piece of content',
        ['manager', 'creator', 'editor', 'client']],
      ['collaborate_execute_announcement', 'Create an announcement to team members',
        ['manager', 'creator', 'editor', 'client']],
      ['collaborate_view_feedback_review', 'Review ALL feedback from editors, other creators, Director, CEO and/or other team members',
        ['manager', 'creator', 'editor', 'client']],
      ['collaborate_edit_feedback_review', 'Review ALL feedback from editors, other creators, Director, CEO and/or other team members',
        ['manager', 'creator', 'editor', 'client']],
      ['collaborate_execute_content', 'Produce a collaborative review, content idea, guest blog, testimonial or other piece of content',
        ['manager', 'editor', 'client']],
      ['collaborate_execute_tasks', 'Assign tasks to Creators/Authors',
        ['manager', 'editor']],
      ['collaborate_execute_projects', 'Assign content projects to Creators/Authors',
        ['manager', 'editor']],
      ['collaborate_execute_approve', 'Approve content',
        ['manager', 'editor']],
      // Calendar
      ['calendar_execute_campaigns_own', 'Create / Edit Own Marketing Campaigns',
        ['manager', 'editor']],
      ['calendar_view_campaigns_other', 'Access to Other User\'s Marketing Campaigns',
        ['manager', 'creator', 'editor', 'client']],
      ['calendar_edit_campaigns_other', 'Access to Other User\'s Marketing Campaigns',
        ['manager', 'editor']],
      ['calendar_execute_schedule', 'Schedule a piece of content on the editorial calendar',
        ['manager', 'creator', 'editor', 'client']],
      ['calendar_view_archive', 'Archive content',
        ['manager', 'creator', 'editor']],
      ['calendar_execute_archive', 'Archive content',
        ['manager', 'creator', 'editor']],
      ['calendar_execute_export', 'Export content or list of content items to Excel or PDF',
        ['manager', 'creator', 'editor', 'client']],
      // Launch
      ['launch_execute_content_own', 'Launch User\'s Own Content',
        ['manager', 'creator', 'editor']],
      ['launch_view_content_other', 'Launch - Other User\'s Published Content',
        ['manager', 'editor']],
      ['launch_execute_content_other', 'Launch - Other User\'s Published Content',
        ['manager']],
      // Measure
      ['measure_view_analytics_own', 'Access to Own Analytics',
        ['manager', 'creator', 'editor', 'client']],
      ['measure_edit_analytics_own', 'Access to Own Analytics',
        ['manager']],
      ['measure_view_analytics_other', 'Access to Other\'s Analytics',
        ['manager', 'creator', 'editor']],
      ['measure_edit_analytics_other', 'Access to Other\'s Analytics',
        ['manager']],
      // Settings
      ['settings_view_personas', 'Create Buyer Personas & buying stages',
        ['manager', 'creator', 'editor', 'client']],
      ['settings_edit_personas', 'Create Buyer Personas & buying stages',
        ['manager']],
      ['settings_view_stylesheets_branding', 'Setup style sheets and branding guidelines',
        ['manager', 'creator', 'editor', 'client']],
      ['settings_edit_stylesheets_branding', 'Setup style sheets and branding guidelines',
        ['manager']],
      ['settings_view_connections', 'View/Edit Content Connections',
        ['manager', 'creator', 'editor']],
      ['settings_edit_connections', 'View/Edit Content Connections',
        ['manager']],
      ['settings_execute_connections', 'Create New Content Connections',
        ['manager']],
      ['settings_view_profiles', 'Access User Profiles',
        ['manager', 'creator', 'editor', 'client']],
      ['settings_edit_profiles', 'Access User Profiles',
        ['manager', 'editor']],
      ['settings_execute_users', 'Create New Users',
        ['manager', 'creator', 'editor']],
      ['settings_view_roles', 'Access User Roles',
        ['editor', 'client']],
      ['settings_edit_roles', 'Access User Roles',
        ['editor']],
      ['settings_execute_roles', 'Create New User Roles',
        ['editor']]
    ];

    // Create special adminster content launch permission
    $adminPermission = new Permission;
    $adminPermission->name = 'adminster_contentlaunch';
    $adminPermission->display_name = 'Administer ContentLaunch';
    $adminPermission->module = 'admin';
    $adminPermission->type = 'execute';
    $adminPermission->save();

    // Give global admin adminster_contentlaunch permission
    $roles['global_admin']->perms()->attach($adminPermission->id);

    // Attach permissions to the builtin roles,
    // These will get copied to roles given to account specific roles
    foreach ($permissions as $permission) {
      list($name, $display_name, $assignRoles) = $permission;
      // Permission keys start with moduleName_
      $parts = explode('_', $name);
      list($module, $type) = $parts;
      $p = new Permission;
      $p->name = $name;
      $p->display_name = $display_name;
      $p->module = $module;
      $p->type = $type;
      $p->save();
      // Attach this permission to roles
      if ($assignRoles) {
        foreach ($assignRoles as $roleName) {
          $roles[$roleName]->perms()->attach($p->id);
        }
      }
      // Give all permissions to site_admin
      $roles['site_admin']->perms()->attach($p->id);
    }

    echo 'Created permissions'. PHP_EOL;

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('assigned_roles', function(Blueprint $table) {
      $table->dropForeign('assigned_roles_user_id_foreign');
      $table->dropForeign('assigned_roles_role_id_foreign');
    });

    Schema::table('permission_role', function(Blueprint $table) {
      $table->dropForeign('permission_role_permission_id_foreign');
      $table->dropForeign('permission_role_role_id_foreign');
    });

    Schema::drop('assigned_roles');
    Schema::drop('permission_role');
    Schema::drop('roles');
    Schema::drop('permissions');
  }

}
