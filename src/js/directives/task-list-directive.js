launch.module.directive('taskList', function($modal, $window, $location, AuthService, TaskService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, NotificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = AuthService.userInfo();
		};

		scope.openCalendar = function (opened, e) {
			e.stopImmediatePropagation();

			return !opened;
		};

		scope.taskGroupIsActive = function (taskGroup) {
			return (scope.parentStatus <= taskGroup.status);
		};

		scope.canEditTask = function (taskGroup) {
			if (!scope.taskGroupIsActive(taskGroup)) {
				return false;
			}

			// TODO: FIGURE OUT PRIVILEGES HERE!!
			//if (!self.loggedInUser.hasPrivilege('create_execute_content_own') && self.loggedInUser.id !== $scope.content.author.id &&
			//	!self.loggedInUser.hasPrivilege('create_edit_content_other')) {
			//	return false;
			//}

			return true;
		};

		scope.getUserName = function (id) {
			var user = launch.utils.getUserById(scope.users, id);

			return (!!user) ? user.formatName() : null;
		};

		scope.saveTaskGroup = function (taskGroup, task) {
			if (!!task && launch.utils.isBlank(task.id)) {
				taskGroup.tasks.push(task);
			}

			var msg = launch.utils.validateAll(taskGroup);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			taskGroup = TaskService.saveContentTasks(self.loggedInUser.account.id, taskGroup, {
				success: function (r) {
					NotificationService.success('Success!', ((!!task) ? 'Successfully modified task, "' + task.name + '"!' : 'Successfully modified "' + taskGroup.name() + '" task group!'));
				},
				error: function (r) {
					self.ajaxHandler.error(r);
				}
			});
		};

		scope.editTask = function (taskGroup, task, e) {
			if (scope.taskGroupIsActive(taskGroup)) {
				if (!task) {
					task = new launch.Task();
					task.taskGroupId = taskGroup.id;
					task.dueDate = new Date();
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
									instance.dismiss('cancel');
								};

								scope1.save = function () {
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

									scope.saveTaskGroup(taskGroup, task);
									instance.close();
								};
							}
						]
					});
				}
			}

			e.stopImmediatePropagation();
		};

		self.init();
	};

	return {
		link: link,
		scope: {
			taskGroups: '=taskGroups',
			parentStatus: '=parentStatus',
			users: '=users'
		},
		templateUrl: '/assets/views/task-list.html'
	};
});