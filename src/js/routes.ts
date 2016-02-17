/// <reference path='launch.ts' />
module launchts {

    launch.module.config(["$stateProvider",
        function($stateProvider:ng.ui.IStateProvider) {
            //$locationProvider.html5Mode(false);
            $stateProvider
                .state('app',{
                    templateUrl: '/assets/views/app.html',
                    resolve: {
                        userInfo: function (AuthService) {
                            return AuthService.validateCurrentUser(); // this will make sure we're  using stale versions
                        }
                    }
                })
                .state('welcome', {
                    parent:'app',
                    controller: 'WelcomeController',
                    controllerAs: 'ctrl',
                    templateUrl: '/assets/views/welcome.html',
                    url: '/welcome'
                })
                .state('home', {
                    url: '',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: "HomeController",
                            controllerAs: "ctrl",
                            templateUrl: '/assets/views/home.html',
                        }
                    }
                })
                .state('agency', {
                    url: '/agency',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'AgencyController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/agency.html',
                        }
                    }
                })
                .state('impersonateReset', {
                    url: '/impersonate/reset',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ResetImpersonateController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/home.html'
                        }
                    }
                })
                .state('signup', {
                    url: '/signup',
                    views: {
                        mainContent: {
                            controller: 'SignupController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/signup.html'
                        }
                    }
                })
                .state('signupConfirm', {
                    url: '/signup/confirm',
                    views: {
                        mainContent: {
                            controller: 'SignupController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/signup_confirm.html'
                        }
                    }
                })
                .state('support', {
                    url: '/support',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'SupportController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/support.html'
                        }
                    }
                })
                .state('account', {
                    url: '/account',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'AccountController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/account.html'
                        }
                    }
                })
                .state('accountConnections', {
                    url: '/account/connections',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentConnectionsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/content-connections.html'
                        }
                    }
                })
                .state('accountContentSettings', {
                    url: '/account/content-settings',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentSettingsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/content-settings.html'
                        }
                    }
                })
                .state('accountPromote', {
                    url: '/account/promote',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'PromoteConnectionsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/promote-connections.html'
                        }
                    }
                })
                .state('user', {
                    url: '/user',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'UserController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/user.html'
                        }
                    }
                })
                .state('confirm', {
                    url: '/user/confirm/:code',
                    views: {
                        mainContent: {
                            controller: 'ConfirmController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/reset-password.html'
                        }
                    }
                })
                .state('users', {
                    url: '/users',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'UsersController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/users.html'
                        }
                    }
                })
                .state('roles', {
                    url: '/roles',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'RolesController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/roles.html'
                        }
                    }
                })
                .state('consult', {
                    url: '/consult',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/consult-landing.html'
                        }
                    }
                })
                .state('consultAdminLibrary', {
                    url: '/consult/admin-library',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultAdminLibraryController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/admin-library.html'
                        }
                    }
                })
                .state('consultLibrary', {
                    url: '/consult/library',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultLibraryController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/library.html'
                        }
                    }
                })
                .state('consultAdminConference', {
                    url: '/consult/admin-conference',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultAdminConferenceController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/admin-conference.html'
                        }
                    }
                })
                .state('consultConferenceList', {
                    url: '/consult/conference',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultConferenceController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/conference-list.html'
                        }
                    }
                })
                .state('consultForum', {
                    url: '/consult/forum',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ForumController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/forum/list.html'
                        }
                    }
                })
                .state('consultForumThread', {
                    url: '/consult/forum/:threadId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ForumThreadController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/forum/thread.html'
                        }
                    }
                })
                .state('consultConference', {
                    url: '/consult/conference/:conferenceId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ConsultConferenceController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/consult/conference-view.html'
                        }
                    }
                })
                .state('create', {
                    url: '/create',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CreateController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/create.html'
                        }
                    }
                })
                .state('createConceptContent', {
                    url: '/create/concept/new/content',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentConceptController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-concept.html'
                        }
                    }
                })
                .state('calendarConceptCampaign', {
                    url: '/calendar/concept/new/campaign',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CampaignConceptController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/campaign-concept.html'
                        }
                    }
                })
                .state('createConceptEditContent', {
                    url: '/create/concept/edit/content/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentConceptController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-concept.html'
                        }
                    }
                })
                .state('calendarConceptEditCampaign', {
                    url: '/calendar/concept/edit/campaign/:campaignId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CampaignConceptController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/campaign-concept.html'
                        }
                    }
                })
                .state('createContent', {
                    url: '/create/content/new',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-edit.html'
                        }
                    }
                })
                .state('createContentEdit', {
                    url: '/create/content/edit/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-edit.html'
                        }
                    }
                })
                .state('createContentView', {
                    url: '/create/content/view/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-view.html'
                        }
                    }
                })
                .state('createContentLaunch', {
                    url: '/create/content/launch/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-launch.html'
                        }
                    }
                })
                .state('createContentPromoteContentId', {
                    url: '/create/content/promote/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/content-promote.html'
                        }
                    }
                })
                .state('collaborate', {
                    url: '/collaborate',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CollaborateController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/collaborate/list.html'
                        }
                    }
                })
                .state('collaborateGuest', {
                    url: '/collaborate/guest/:accessCode',
                    views: {
                        mainContent: {
                            controller: 'GuestCollaboratorController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/collaborate/guest-landing.html'
                        }
                    }
                })
                .state('collaborateGuestContent', {
                    url: '/collaborate/guest/content/:contentId',
                    views: {
                        mainContent: {
                            controller: 'GuestContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/collaborate/edit-concept.html'
                        }
                    }
                })
                .state('collaborateGuestCampaign', {
                    url: '/collaborate/guest/campaign/:campaignId',
                    views: {
                        mainContent: {
                            controller: 'GuestCampaignController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/collaborate/edit-concept.html'
                        }
                    }
                })
                .state('collaborateConceptType', {
                    url: '/collaborate/:conceptType/:id',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CollaborateController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/collaborate/single.html'
                        }
                    }
                })
                .state('calendar', {
                    url: '/calendar',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CalendarController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/calendar.html'
                        }
                    }
                })
                .state('calendarCampaigns', {
                    url: '/calendar/campaigns/:campaignId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CampaignController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/calendar/campaign.html',
                        }
                    }
                    //reloadOnSearch: false
                })
                .state('promote', {
                    url: '/promote',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'CreateController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/promote/promote.html'
                        }
                    }
                })
                .state('promoteContentNew', {
                    url: '/promote/content/new',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/promote/promote-content.html'
                        }
                    }
                })
                .state('promoteContent', {
                    url: '/promote/content/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'ContentController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/promote/promote-content.html'
                        }
                    }
                })
                .state('promoteCampaign', {
                    url: '/promote/campaign/:campaignId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'PromoteCampaignController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/promote/promote-campaign.html'
                        }
                    }
                })
                .state('measure', {
                    url: '/measure',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureOverviewController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-overview.html'
                        }
                    }
                })
                .state('measureCreationStats', {
                    url: '/measure/creation-stats',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureCreationStatsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-creation-stats.html'
                        }
                    }
                })
                .state('measureContentTrends', {
                    url: '/measure/content-trends',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureContentTrendsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-content-trends.html'
                        }
                    }
                })
                .state('measureContentDetails', {
                    url: '/measure/content-details',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureContentDetailsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-content-details.html'
                        }
                    }
                })
                .state('measureMarketingAutomation', {
                    url: '/measure/marketing-automation',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureMarketingAutomationController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-marketing-automation.html'
                        }
                    }
                })
                .state('measureContent', {
                    url: '/measure/content/:contentId',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'MeasureContentItemController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/measure/measure-content-item-details.html'
                        }
                    }
                })
                .state('accounts', {
                    url: '/accounts',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'AccountsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/account/accounts.html'
                        }
                    }
                })
                .state('subscription', {
                    url: '/subscription',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'SubscriptionController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/subscription.html'
                        }
                    }
                })
                .state('announce', {
                    url: '/announce',
                    parent: 'app',
                    views: {
                        mainContent: {
                            controller: 'AnnouncementsController',
                            controllerAs: 'ctrl',
                            templateUrl: '/assets/views/announcements.html'
                        }
                    }
                });
        }
    ]);
}