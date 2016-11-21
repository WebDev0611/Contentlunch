# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  # bento/centos-7.1 wasn't working with the latest virtualbox
  config.vm.box = "boxcutter/centos72"

  # Create a forwarded port mapping - The private_network below didn't work for me, but this forwarded version does.
  #config.vm.network "forwarded_port", guest: 80, host: 8080
  #config.vm.network "forwarded_port", guest: 3306, host: 3307

  # Create a private network, which allows host-only access to the machine.
  config.vm.network "private_network", ip: "192.168.33.16"

  # Share an additional folder to the guest VM.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configurations
  config.vm.provider "virtualbox" do |v|
    v.memory = 512
    v.cpus = 1
  end

  config.vm.provider "virtualbox" do |v|
    v.gui = false
  end

  config.vm.provision "shell", path: "provision.sh", run: "always"
end
