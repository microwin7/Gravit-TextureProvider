#!/bin/bash

# The Dockerfile ENVs take precedence here, but defaulting for testing consistency
if [ -z "${USER_ID}" ]; then
    USER_ID=33
fi

if [ -z "${GROUP_ID}" ]; then
    GROUP_ID=33
fi

printf "USER_ID: ${USER_ID}\n"
printf "GROUP_ID: ${GROUP_ID}\n"

runAsUser=www-data
runAsGroup=www-data

if [[ -v USER_ID ]]; then
    if [[ $USER_ID != 0 ]]; then
        if [[ $USER_ID != $(id -u www-data) ]]; then
            printf "Changing uid of www-data to $USER_ID\n"
            usermod -u $USER_ID www-data
        fi
    fi
fi
if [[ -v GROUP_ID ]]; then
    if [[ $GROUP_ID != 0 ]]; then
        if [[ $GROUP_ID != $(id -g www-data) ]]; then
            printf "Changing gid of www-data to $GROUP_ID\n"
            groupmod -o -g "$GROUP_ID" www-data
        fi
    fi
fi
if [[ $(stat -c "%u" ${PWD}/cache) != "$USER_ID" ]]; then
    printf "Changing ownership of ${PWD}/cache to $USER_ID ...\n"
    chown -R ${runAsUser}:${runAsGroup} ${PWD}/cache
fi
if [[ $(stat -c "%u" ${PWD}/../db_logs) != "$USER_ID" ]]; then
    printf "Changing ownership of ${PWD}/../db_logs to $USER_ID ...\n"
    chown -R ${runAsUser}:${runAsGroup} ${PWD}/../db_logs
fi
if [[ $(stat -c "%u" ${PWD}/storage) != "$USER_ID" ]]; then
    printf "Changing ownership of ${PWD}/storage to $USER_ID ...\n"
    chown -R ${runAsUser}:${runAsGroup} ${PWD}/storage
fi

printf "Clean Route Cache ...\n"
rm -f ${PWD}/cache/route.cache

printf "\033[39mYou \033[33mGateway\033[39m Addr for connect \033[33mDB\033[39m in Hosting: \033[33m$(ip -4 route show default | cut -d" " -f3)\033[39m\n"

exec "$@"