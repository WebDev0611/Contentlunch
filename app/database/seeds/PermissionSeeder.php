<?php

class PermissionSeeder extends Seeder {

  public function run()
  {

    // Permissions are keyed by:
    // [module]_[action]_[target]_[extra keys]
    // Attaches permissions to the default (builtin) roles
    $permissions = array(
      // Create
      array('create_execute_content_own',
        'Create / edit own content',
        // Assign to these roles:
        array('manager', 'creator', 'editor', 'client')),
      array('create_view_content_other_unapproved',
        'Access other User\'s Content, Not Yet Approved',
        array('manager', 'creator', 'editor', 'client')),
      array('create_edit_content_other_unapproved',
        'Access other User\'s Content, Not Yet Approved',
        array('manager', 'editor')),
      array('create_view_content_other',
        'Access other User\'s Completed Content',
        array('manager', 'creator', 'editor', 'client')),
      array('create_edit_content_other',
        'Access other User\'s Completed Content',
        array('manager')),
      array('create_execute_ideas_own',
        'Create / edit own ideas',
        array('manager', 'creator', 'editor', 'client')),
      array('create_view_ideas_other',
        'Access Ideas Created by Other Users',
        array('manager', 'editor')),
      array('create_edit_ideas_other',
        'Access Ideas Created by Other Users',
        array('manager', 'editor')),
      // Collaborate
      array('collaborate_execute_sendcontent',
        'Send a piece of content to internal team',
        array('manager', 'creator', 'editor', 'client')),
      array('collaborate_execute_feedback',
        'Give ideas and feedback to other team members on a piece of content',
        array('manager', 'creator', 'editor', 'client')),
      array('collaborate_execute_announcement',
        'Create an announcement to team members',
        array('manager', 'creator', 'editor', 'client')),
      array('collaborate_view_feedback_review',
        'Review ALL feedback from editors, other creators, Director, CEO and/or other team members',
        array('manager', 'creator', 'editor', 'client')),
      array('collaborate_edit_feedback_review',
        'Review ALL feedback from editors, other creators, Director, CEO and/or other team members',
        array('manager', 'creator', 'editor', 'client')),
      array('collaborate_execute_content',
        'Produce a collaborative review, content idea, guest blog, testimonial or other piece of content',
        array('manager', 'editor', 'client')),
      array('collaborate_execute_tasks',
        'Assign tasks to Creators/Authors',
        array('manager', 'editor')),
      array('collaborate_execute_projects',
        'Assign content projects to Creators/Authors',
        array('manager', 'editor')),
      array('collaborate_execute_approve',
        'Approve content',
        array('manager', 'editor')),
      // Calendar
      array('calendar_execute_campaigns_own',
        'Create / Edit Own Marketing Campaigns',
        array('manager', 'editor')),
      array('calendar_view_campaigns_other',
        'Access to Other User\'s Marketing Campaigns',
        array('manager', 'creator', 'editor', 'client')),
      array('calendar_edit_campaigns_other',
        'Access to Other User\'s Marketing Campaigns',
        array('manager', 'editor')),
      array('calendar_execute_schedule',
        'Schedule a piece of content on the editorial calendar',
        array('manager', 'creator', 'editor', 'client')),
      array('calendar_view_archive',
        'Archive content',
        array('manager', 'creator', 'editor')),
      array('calendar_execute_archive',
        'Archive content',
        array('manager', 'creator', 'editor')),
      array('calendar_execute_export',
        'Export content or list of content items to Excel or PDF',
        array('manager', 'creator', 'editor', 'client')),
      // Launch
      array('launch_execute_content_own',
        'Launch User\'s Own Content',
        array('manager', 'creator', 'editor')),
      array('launch_view_content_other',
        'Launch - Other User\'s Published Content',
        array('manager', 'editor')),
      array('launch_execute_content_other',
        'Launch - Other User\'s Published Content',
        array('manager')),
      // Measure
      array('measure_view_analytics_own',
        'Access to Own Analytics',
        array('manager', 'creator', 'editor', 'client')),
      array('measure_edit_analytics_own',
        'Access to Own Analytics',
        array('manager')),
      array('measure_view_analytics_other',
        'Access to Other\'s Analytics',
        array('manager', 'creator', 'editor')),
      array('measure_edit_analytics_other',
        'Access to Other\'s Analytics',
        array('manager')),
      // Settings
      array('settings_view_personas',
        'Create Buyer Personas & buying stages',
        array('manager', 'creator', 'editor', 'client')),
      array('settings_edit_personas',
        'Create Buyer Personas & buying stages',
        array('manager')),
      array('settings_view_stylesheets_branding',
        'Setup style sheets and branding guidelines',
        array('manager', 'creator', 'editor', 'client')),
      array('settings_edit_stylesheets_branding',
        'Setup style sheets and branding guidelines',
        array('manager')),
      array('settings_view_connections',
        'View/Edit Content Connections',
        array('manager', 'creator', 'editor')),
      array('settings_edit_connections',
        'View/Edit Content Connections',
        array('manager')),
      array('settings_execute_connections',
        'Create New Content Connections',
        array('manager')),
      array('settings_view_profiles',
        'Access User Profiles',
        array('manager', 'creator', 'editor', 'client')),
      array('settings_edit_profiles',
        'Access User Profiles',
        array('manager', 'editor')),
      array('settings_execute_users',
        'Create New Users',
        array('manager', 'creator', 'editor')),
      array('settings_view_roles',
        'Access User Roles',
        array('editor', 'client')),
      array('settings_edit_roles',
        'Access User Roles',
        array('editor')),
      array('settings_execute_roles',
        'Create New User Roles',
        array('editor'))
    );

    $allRoles = Role::where('builtin', 1)
      ->where('account_id', NULL)
      ->get();
    $roles = array();
    foreach ($allRoles as $role) {
      $roles[$role->name] = $role;
    }
    $roles['site_admin'] = Role::where('name', 'site_admin')->first();

    foreach ($permissions as $permission) {
      list($name, $display_name, $assignRoles) = $permission;
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
  }

}
