#!/bin/sh

SITE_PATH=$1
CODE_PATH=$2

EXTENSION_NAME="plg_content_wistiaembed"

if [ -z $SITE_PATH ]; then
    echo "Please enter the path to the root of your Joomla! install: "
    read SITE_PATH
fi

if [ ! -d $SITE_PATH ]; then
    echo "Invalid directory. Exiting..."
    exit
fi

if [ -z $CODE_PATH ]; then
    CWD=$(pwd)

    CODE_PATH=$CWD/src
    if [ ! -d $CODE_PATH ]; then
        PARENT=$(dirname $CWD)
        CODE_PATH=$PARENT/src
        if [ ! -d $CODE_PATH ]; then
            echo "Could not find code path.  Please enter path to the code directory of the $EXTENSION_NAME repository:"
            read CODE_PATH
            if [ ! -d $CODE_PATH ]; then
                echo "Path to code not found"
            fi
        fi
    fi

fi

# Params: $1: source, $2: destination
create_symlink () {
    SOURCE_PATH=$CODE_PATH/$1
    DESTINATION_PATH=$SITE_PATH/$2

    if [ -d $DESTINATION_PATH ]; then
        echo "Deleting old directory: $DESTINATION_PATH"
        rm -rf $DESTINATION_PATH
    fi

    if [ -L $DESTINATION_PATH ]; then
        echo "Deleting old symlink: $DESTINATION_PATH"
        rm -f $DESTINATION_PATH
    fi

    echo "Symlinking: $DESTINATION_PATH"
    ln -s $SOURCE_PATH $DESTINATION_PATH

    echo ""
}

create_symlink language/en-GB/en-GB.$EXTENSION_NAME.sys.ini administrator/language/en-GB/en-GB.$EXTENSION_NAME.sys.ini
create_symlink ./ plugins/content/wistiaembed

echo "Links created successfully"
exit
