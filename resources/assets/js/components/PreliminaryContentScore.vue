<template>
    <div>

    </div>
</template>

<script>
    export default {
            name: 'prelimContentScoreApp',
            data: {
                message: "",
                positiveFeedback: "",
                negativeFeedback: "",
                reachPositiveFeedback: "You had lots of page views with your content and many of these views were from unique visitors! Nice work.",
                reachNegativeFeedback: "Unfortunately, you don’t have many page views and not very many unique visitors either. But it can be improved!",
                interestPositiveFeedback: "Your visitors average session duration with your content is higher than the industry average! Nice work.",
                interestNegativeFeedback: "Unfortunately, your visitors average session duration with your content is much lower than the industry average. But it can be improved!",
                searchPositiveFeedback: "Over 60% of your site visitors came from organic search! (vs. Direct traffic or paid search) Nice work.",
                searchNegativeFeedback: "Unfortunately, you don’t have many site visitors coming from organic search. But this can be improved!",
                impactPositiveFeedback: "Visitors to your sites content are engaged! They respond to your calls to action and read other content on your site. Nice work.",
                impactNegativeFeedback: "Unfortunately, visitors to your sites content are not very engaged. Many of them leave your site quickly. But this can be improved!",
                gaAccountName: ""

            },
            mounted: function () {
                this.loadingRing = $(".loading-ring");
                this.addConnectionRow = $(".row.add-connection");
                this.connectionRow = $(".row.connection");
                this.accountRow = $(".row.account");
                this.propertyRow = $(".row.property");
                this.profileRow = $(".row.profile");
                this.scoreRow = $(".row.score");
                this.feedbackRow = $(".row.feedback");
                this.contentScoreDonutChart = $(".donut-chart.content-score");
                this.getGaConnections();
            },
            methods: {
                debug(data) {
                    window.debug = data;
                    console.log(data);
                },

                setGaAccountName(name) {
                    this.gaAccountName = name;
                },

                getGaConnections() {
                    this.message = "Checking for Active Google Analytics Connections.";
                    $.ajax({
                        url: "/api/connections/ga",
                        success: this.handleGaConnections
                    });
                },

                handleGaConnections(connections) {
                    //console.log(connections);
                    if (!connections.length) {
                        // console.log("No connections!");
                        // No Accounts get a message inviting to setup an account with a link.
                        this.message = "It doesn't appear that you have a Google Analytics connection setup. </br >" +
                            "Please create a connection in order to calculate your ContentLaunch Content Score";
                        this.addConnectionRow.removeClass("hide");
                        this.loadingRing.hide();
                    } else if (connections.length === 1) {
                        //console.log("Going for one connections!");
                        $.cookie('gaConnectionId', connections[0].id, {expires: 1});
                        this.getAccounts();
                    } else {
                        let options = ['<option value=""> - Choose Connection - </option>'];

                        for (let i = 0; i < connections.length; i++) {
                            let option = `<option value="${connections[i].id}">${connections[i].name}</option>`;
                            options.push(option);
                        }

                        this.connectionRow.removeClass("hide").find("select").append($(options.join("")));

                        this.message = "Please choose the Google Analytics Connection to use.";
                        this.loadingRing.hide();

                    }

                },

                getAccounts(connection) {
                    this.connectionRow.addClass("hide");
                    $.cookie('gaConnectionId', connection, {expires: 1});
                    this.message = "Getting the accounts.";
                    $.ajax({
                        url: "/api/content-score/accounts",
                        success: this.handleAccounts
                    });
                },

                handleAccounts(accounts) {
                    if (!accounts.length) {
                        // No Accounts get a message inviting to setup an account with a link.
                        this.message = "It doesn't appear that you have a Google Analytics account setup. </br >" +
                            "Please follow the instructions at the link below to setup an account.";
                        // TODO Provide the link
                    } else if (accounts.length === 1) {
                        //console.log("Going for one account!");
                        // One Account call getProfiles with that account
                        this.getProperties(accounts[0].id);
                        this.setGaAccountName(accounts[0].name);

                    } else {
                        let options = ['<option value=""> - Choose Account - </option>'];

                        for (let i = 0; i < accounts.length; i++) {
                            let option = `<option value="${accounts[i].id}">${accounts[i].name}</option>`;
                            options.push(option);
                        }

                        this.accountRow.removeClass("hide").find("select").append($(options.join("")));

                        this.message = "Please choose the Google Analytics Account to use.";
                        this.loadingRing.hide();

                    }
                },

                getProperties(account) {
                    this.accountRow.addClass("hide");
                    this.loadingRing.show();

                    this.message = "Getting the properties.";
                    $.ajax({
                        url: `/api/content-score/properties/${account}`,
                        success: this.handleProperties
                    });
                },

                handleProperties(properties) {
                    //console.log(properties);
                    if (!properties.length) {
                        //console.log("No properties!");
                        this.message = "It doesn't appear that you have a Google Analytics property/app setup. </br >" +
                            "Please follow the instructions at the link below to setup a property.";
                        // TODO Provide the link
                    } else if (properties.length === 1) {
                        //console.log("One properties!");
                        this.getProfiles(
                            this.propertyRow
                                .find("select")
                                .html(`<option SELECTED value="${properties[0].id}"></option>`)
                                .data("account", properties[0].accountId)
                        );

                    } else {
                        let options = ['<option value=""> - Choose Property - </option>'];

                        for (let i = 0; i < properties.length; i++) {
                            let option = `<option value="${properties[i].id}">${properties[i].name}</option>`;
                            options.push(option);
                        }

                        this.propertyRow.removeClass("hide").find("select").html(options.join("")).data("account", properties[0].accountId);
                        this.message = "Please choose the Google Analytics Property to use.";
                        this.loadingRing.hide();
                    }
                },

                getProfiles(target) {
                    this.propertyRow.addClass("hide");
                    this.loadingRing.show();

                    this.message = "Getting the profiles.";
                    $.ajax({
                        url: `/api/content-score/profiles/${$(target).data().account}/${$(target).val()}`,
                        success: this.handleProfiles
                    });
                },

                handleProfiles(profiles) {
                        //console.log(profiles);
                    if (!profiles.length) {
                        //console.log("No profiles!");
                        this.message = "It doesn't appear that you have a Google Analytics profile setup. </br >" +
                            "Please follow the instructions at the link below to setup a profile.";
                        // TODO Provide the link
                    } else if (profiles.length === 1) {
                        //console.log("One profile!");
                        this.getContentScore(profiles[0].id);

                    } else {
                        let options = ['<option value=""> - Choose View - </option>'];

                        for (let i = 0; i < profiles.length; i++) {
                            let option = `<option value="${profiles[i].id}">${profiles[i].name}</option>`;
                            options.push(option);
                        }

                        this.profileRow.removeClass("hide").find("select").append($(options.join("")));

                        this.message = "Please choose the Google Analytics View to use.";
                        this.loadingRing.hide();
                    }
                },

                getContentScore(profile) {
                    this.profileRow.addClass("hide");
                    this.loadingRing.show();

                    this.message = "Calculating your content score.";
                    $.ajax({
                        url: `/api/content-score/${profile}`,
                        date: {path: "/login"},
                        success: data => {
                            this.handleScore(data);
                        }
                    });
                },

                handleScore(scoreResults) {
                    // TODO ADD SCORED URL INTO THE MESSAGE HERE.
                    this.message = "This is your preliminary content score for '" + this.gaAccountName + "'. <br /> By using ContentLaunch you will be able to improve it.";
                    this.loadingRing.hide();
                    this.scoreRow.hide();
                    this.scoreRow.removeClass("hide");
                    this.scoreRow.fadeIn(1000, () => {
                        this.changeScore(scoreResults);
                    });
                },

                changeScore(scoreResults) {
                    let score = scoreResults.score,
                        current = 0,
                        lowestScore = Object.keys(scoreResults).reduce(function (a, b) {
                            return scoreResults[a] < scoreResults[b] ? a : b
                        }),
                        highestScore = Object.keys(scoreResults).reduce(function (a, b) {
                            return scoreResults[a] > scoreResults[b] ? a : b
                        }),
                        timeout = () => {

                            this.contentScoreDonutChart
                                .removeClass((i, c) => {
                                    return (c.match(/\b(primary\S+|primary)/g) || []).join(' ')
                                })
                                .addClass(`primary-${Math.round(current)}`)
                            ;

                            current++;

                            if (current < score * 10) {
                                setTimeout(timeout, current / (score * 10) * 100);
                            } else {
                                this.negativeFeedback = this[`${lowestScore}NegativeFeedback`];
                                this.positiveFeedback = this[`${highestScore}PositiveFeedback`];
                                this.feedbackRow.hide();
                                this.feedbackRow.removeClass("hide");
                                this.feedbackRow.slideDown(500);
                            }
                        };

                    timeout();
                }

            }
        })
    }
</script>