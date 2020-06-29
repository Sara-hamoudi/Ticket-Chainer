#!/usr/bin/python3
import boto3
from prettytable import PrettyTable

ec2 = boto3.resource("ec2")
ec2Filter = [
    # {"Name": "tag:Env", "Values": ["stage"]}
]
sshCmdTemplate = "ssh -i ~/.ssh/ticketchainer_aws.pem ubuntu@%s"
pTable = PrettyTable()
pTable.field_names = ["Environment", "Name", "State", "SSH Command"]
for instance in ec2.instances.filter(Filters=ec2Filter):
    instanceTags = {}

    if instance.tags:
        for tag in instance.tags:
            instanceTags[tag["Key"]] = tag["Value"]

    ipv4pub = instance.public_ip_address or None

    pTable.add_row([
        instanceTags.get("Env") or '-',
        instanceTags.get("Name"),
        instance.state["Name"],
        sshCmdTemplate % ipv4pub if ipv4pub else '-',
    ])

pTable.sortby = "Environment"
print(pTable)
