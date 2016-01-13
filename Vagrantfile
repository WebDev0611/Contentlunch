# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "bento/centos-7.1"

  # Create a forwarded port mapping
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine.
  config.vm.network "private_network", ip: "192.168.33.10"

  # Share an additional folder to the guest VM.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configurations
  config.vm.provider "virtualbox" do |v|
    v.memory = 2024
    v.cpus = 2
    v.customize ["modifyvm", :id, "--nictype1", "virtio"]
  end

  config.vm.provision "shell", path: "provision.sh"
end
