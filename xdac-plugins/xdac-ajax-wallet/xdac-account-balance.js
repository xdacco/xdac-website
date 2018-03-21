jQuery(document).ready(function() {
    var web3l = new Web3(new Web3.providers.HttpProvider("https://api.myetherapi.com/eth"));
    function ether (n) {
        return new web3l.BigNumber(web3l.toWei(n, 'ether'));
    }

    var init = async function (contract_address) {
        var XdacTokenCrowdsaleJson  = await jQuery.getJSON(ajax_var.plugin_url+"/XdacTokenCrowdsale.json");
        var XdacTokenJson = await jQuery.getJSON(ajax_var.plugin_url+"/XdacToken.json");
        var XdacTokenCrowdsale = web3l.eth.contract(XdacTokenCrowdsaleJson.abi);
        var XdacToken = web3l.eth.contract(XdacTokenJson.abi);
        var XdacTokenCrowdsaleInstance = await XdacTokenCrowdsale.at(contract_address);
        var token = await XdacTokenCrowdsaleInstance.token.call();
        var XdacTokenInstance = await XdacToken.at(token);
        if(ajax_var.logged_in != 0) {
            jQuery("#wallet").val(ajax_var.wallet_address);
            jQuery(".your_balance_div p").append(" <strong>"+ajax_var.first_name.toUpperCase() + "</strong>:");
        }
        return {
            XdacTokenCrowdsaleInstance: XdacTokenCrowdsaleInstance,
            XdacTokenInstance: XdacTokenInstance,
        }
    }
    init('0x59760c7a2CFC181E6A6eea0F4465047eeE5DA2c2').then(function (init) {
        var XdacTokenCrowdsaleInstance =  init.XdacTokenCrowdsaleInstance
        var XdacTokenInstance =  init.XdacTokenInstance;

        var contract_address =  '0x59760c7a2CFC181E6A6eea0F4465047eeE5DA2c2';
        var getBalance = async function(address, contract_address) {
            var contributorValues = await XdacTokenCrowdsaleInstance.contributors.call(address)
            var eth_balance = contributorValues[0].valueOf() / ether(1);
            var whitelisted = contributorValues[1].valueOf();
            var rate = await XdacTokenCrowdsaleInstance.getCurrentRate()
            var balance = await XdacTokenInstance.balanceOf(address)
            var xdac_balance = balance / ether(1);
            return {'address': address, 'eth': eth_balance, 'xdac': xdac_balance, 'whitelisted': whitelisted, 'rate': rate }
        }
        var getTokenAmount =  async function (eth) {
            var tokenAmount = await XdacTokenCrowdsaleInstance.getTokenAmount(eth.toNumber())
            return new web3l.BigNumber(tokenAmount).dividedBy(ether(1));
        }
        var getEthAmount =  async function (xdac) {
            var weiAmount = await XdacTokenCrowdsaleInstance.getEthAmount(xdac.toNumber())
            return new web3l.BigNumber(weiAmount).dividedBy(ether(1));
        }
        jQuery("#eth_am").val(1);
        getTokenAmount(ether(jQuery("#eth_am").val())).then(function (data) {
            jQuery("#xdac_am").val(data)
        })
        function displayBalance(data) {
            if(data.whitelisted) {
                jQuery(".account_xdac_balance p").html(data.xdac + " XDAC")
                jQuery(".whitelisted_button").html("Your account was successfully whitelisted " +
                    "<span style='font-size: 18px; color: green;' class='fa-check'></span>");
            }
            else {
                if(data.eth) {
                    jQuery(".account_xdac_balance p").html("" + data.eth*data.rate + " XDAC (" + data.eth + " ETH) pending, " +
                        "please whitelist your address")
                }
                else {
                    jQuery(".account_xdac_balance p").html(data.xdac + " XDAC, please whitelist your address");
                }
            }
            jQuery(".wallet_address_message").html("");
        }

        jQuery("#eth_am").on("blur", function () {
            getTokenAmount(ether(jQuery("#eth_am").val())).then(function (data) {
                jQuery("#xdac_am").val(data)
            })
        })
        jQuery("#xdac_am").on("blur", function () {
            getEthAmount(ether(jQuery("#xdac_am").val())).then(function (data) {
                jQuery("#eth_am").val(data)
            })
        })
        var address = jQuery("#wallet").val();
        if(address) {
            if(address) {
                getBalance(jQuery("#wallet").val(), contract_address).then(function (data) {
                    displayBalance(data)
                });
            } else {
                jQuery(".wallet_address_message").html("Please specify a valid wallet address.")
            }
        }

        jQuery("#getBalance").on("click", function () {
            if(web3l.isAddress(jQuery("#wallet").val())) {
                if(ajax_var.logged_in) {
                    jQuery.post(ajax_var.url, {
                        action: 'save_wallet_address',
                        security : ajax_var.security,
                        address: jQuery("#wallet").val()
                    }, function (res) {
                        jQuery("#wallet").val(res)
                    });
                }
                getBalance(jQuery("#wallet").val(), contract_address).then(function (data) {
                    displayBalance(data)
                });
            }
            else {
                jQuery(".wallet_address_message").html("Please specify a valid wallet address.")
            }
        })
    })






});

