launch.module.directive('taskList', function($modal, $window, $location, AuthService, TaskService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;
		self.initialStateIsSet = false;
		self.openTaskGroups = [];

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, NotificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = AuthService.userInfo();

			scope.canCreateTasks = self.loggedInUser.hasPrivilege('collaborate_execute_tasks_collaborators');
			scope.canAssignTasks = self.loggedInUser.hasPrivilege('collaborate_execute_tasks_collaborators');
			scope.canEditTasksOthers = self.loggedInUser.hasPrivilege('collaborate_execute_tasks_complete');
			// TODO: WHAT'S THE RIGHT PRIVILEGE FOR THIS??
			scope.canDeleteTasks = self.loggedInUser.hasPrivilege('collaborate_execute_tasks_complete');
		};

		self.refreshTaskGroups = function(contentId) {
			scope.taskGroups = TaskService.queryContentTasks(self.loggedInUser.account.id, contentId, {
				success: function(r) {
					self.setOpenTaskGroups();
				},
				error: self.ajaxHandler.error
			});
		};

		self.setOpenTaskGroups = function() {
			if (!!scope.taskGroups && $.isArray(scope.taskGroups) && scope.taskGroups.length > 0) {
				$.each(scope.taskGroups, function (i, tg) {
					if ($.isArray(self.openTaskGroups) && self.openTaskGroups.length > 0) {
						tg.isOpen = $.inArray(tg.id, self.openTaskGroups) >= 0;
					} else if (scope.parentStatus === tg.status && !self.initialStateIsSet) {
						tg.isOpen = true;
						scope.toggleOpen(tg);
					}
				});
			}
		};

		scope.canCreateTasks = false;
		scope.canAssignTasks = false;
		scope.canEditTasksOthers = false;
		scope.canDeleteTasks = false;

		scope.openCalendar = function (opened, e) {
			e.stopImmediatePropagation();

			return !opened;
		};

		scope.taskGroupIsActive = function (taskGroup) {
			return (scope.parentStatus <= taskGroup.status);
		};

		scope.canEditTask = function (taskGroup, task) {
			if (!scope.taskGroupIsActive(taskGroup) || scope.parentStatus !== taskGroup.status) {
				return false;
			}

			return (!!task && self.loggedInUser.id !== task.userId) ? scope.canEditTasksOthers : true;
		};

		scope.getUserName = function (id) {
			var user = launch.utils.getUserById(scope.users, parseInt(id));

			return (!!user) ? user.formatName() : null;
		};

		scope.toggleTaskActiveStatus = function (taskGroup, task) {
			if (task.userId !== self.loggedInUser.id && !scope.canEditTasksOthers) {
				NotificationService.error('Error!', 'You do not have sufficient privileges to edit a task assigned to someone else. Please contact your administrator for more information.');
				task.isComplete = !task.isComplete;
				return;
			}

			task.completeDate = (task.isComplete) ? new Date() : null;

			if ($.grep(taskGroup.tasks, function (t) { return !t.isComplete; }).length === 0) {
				taskGroup.isComplete = true;
				taskGroup.completeDate = new Date();
			} else {
				taskGroup.isComplete = false;
				taskGroup.completeDate = null;
			}

			scope.saveTaskGroup(taskGroup, task);
		};

		scope.changeTaskGroupDueDate = function (taskGroup, task) {
			var msg = '';

			$.each(taskGroup.tasks, function (i, t) {
				msg += (t.dueDate > taskGroup.dueDate) ? 'Task ' + t.name + ' due date is later than ' + taskGroup.name() + ' due date.\n' : '';
			});

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', msg);
				self.refreshTaskGroups(taskGroup.contentId);
				return;
			}

			scope.saveTaskGroup(taskGroup, task);
		};

		scope.saveTaskGroup = function (taskGroup, task, callback) {
			if (!!task && launch.utils.isBlank(task.id)) {
				taskGroup.tasks.push(task);
			}

			var msg = launch.utils.validateAll(taskGroup);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				self.refreshTaskGroups(taskGroup.contentId);
				return;
			}

			taskGroup = TaskService.saveContentTasks(self.loggedInUser.account.id, taskGroup, {
				success: function (r) {
					NotificationService.success('Success!', ((!!task) ? 'Successfully modified task, "' + task.name + '"!' : 'Successfully modified "' + taskGroup.name() + '" task group!'));

					self.refreshTaskGroups(taskGroup.contentId);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(r);
					}
				},
				error: function (r) {
					self.ajaxHandler.error(r);

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		scope.editTask = function (taskGroup, task, e) {
			if (scope.canEditTask(taskGroup, task)) {
				if (!task) {
					task = new launch.Task();
					task.taskGroupId = taskGroup.id;
					task.dueDate = new Date(taskGroup.dueDate);
					task.isComplete = false;
				}

				if (!task.isComplete) {
					$modal.open({
						templateUrl: 'create-task.html',
						controller: [
							'$scope', '$modalInstance', function (scope1, instance) {
								scope1.task = task;

								scope1.users = scope.users;
								scope1.openCalendar = scope.openCalendar;
								scope1.formatUserItem = scope.formatUserItem;

								scope1.cancel = function () {
									self.refreshTaskGroups(taskGroup.contentId);
									instance.dismiss('cancel');
								};

								scope1.save = function (createAnother) {
									var msg = launch.utils.validateAll(scope1.task);

									if (!launch.utils.isBlank(msg)) {
										NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
										return;
									}

									if (scope1.task.dueDate > taskGroup.dueDate) {
										$modal.open({
											templateUrl: 'confirm.html',
											controller: [
												'$scope', '$modalInstance', function (scope2, inst) {
													scope2.message = 'A Task\'s Due Date cannot be after the Task Group\'s Due Date. Do you want to extend the Task Group\'s Due Date?';
													scope2.okButtonText = 'Yes';
													scope2.cancelButtonText = 'No';
													scope2.onOk = function () {
														taskGroup.dueDate = task.dueDate;
														inst.close();
														instance.close();
														scope.saveTaskGroup(taskGroup, task);
													};
													scope2.onCancel = function () {
														inst.dismiss('cancel');
													};
												}
											]
										});

										return;
									}

									scope.saveTaskGroup(taskGroup, task, {
										success: function() {
											if (createAnother) {
												scope1.task = task = new launch.Task();
												scope1.task.taskGroupId = taskGroup.id;
												scope1.task.dueDate = new Date(taskGroup.dueDate);
												scope1.task.isComplete = false;
											} else {
												instance.close();
											}
										}
									});
								};
							}
						]
					});
				}
			}

			e.stopImmediatePropagation();
		};

		scope.deleteTask = function(taskGroup, task) {
			if (!scope.canDeleteTasks || scope.parentStatus > taskGroup.status) {
				var msg = !scope.canDeleteTasks ? 'You do not have sufficient privileges to delete a task. Please contact your administrator for more information.' :
					'You cannot delete tasks that live in a closed task group.';

				NotificationService.error('Error!', msg);
				return;
			}

			var handleDelete = function() {
				taskGroup.tasks = $.grep(taskGroup.tasks, function (t) { return t.id !== task.id; });
				scope.saveTaskGroup(taskGroup, null);
			};

			//if (task.isComplete) {
				$modal.open({
					templateUrl: 'confirm.html',
					controller: [
						'$scope', '$modalInstance', function (scp, instance) {
							scp.message = 'Are you sure you want to delete this task?';
							scp.okButtonText = 'Delete';
							scp.cancelButtonText = 'Cancel';
							scp.onOk = function () {
								handleDelete();
								instance.close();
							};
							scp.onCancel = function () {
								instance.dismiss('cancel');
							};
						}
					]
				});
			//} else {
			//	handleDelete();
			//}
		};

		scope.toggleOpen = function (taskGroup) {
			var index = $.inArray(taskGroup.id, self.openTaskGroups);

			if (index < 0) {
				self.openTaskGroups.push(taskGroup.id);
			} else {
				self.openTaskGroups.splice(index, 1);
			}
		};

		scope.$watch('taskGroups', function() {
			self.setOpenTaskGroups();
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			taskGroups: '=taskGroups',
			parentStatus: '=parentStatus',
			authorId: '=authorId',
			users: '=users'
		},
		templateUrl: '/assets/views/task-list.html'
	};
});